<?php

/**
 * Class Entity
 *
 * Alap Entity osztaly, minden konkret Entity ebbol orokol
 *
 * Az Entity egyfajta Facade -kent mukodik, magaban foglalja az ORM es egyeb szukseges osztalyokat.
 * A kliensek tole fuggnek
 *
 * @author      Joo Martin
 * @package     Core
 * @since       2.2
 * @version     1.0
 */

abstract class Entity
{
    protected $_model;

    // Azok az adattagok, amiket nem kell self::map() -nak masolni
    private static $_disabledPropertiesInMap = ['_model', '_fromObject', '_toObject', '_stdObject'];

    // self::map() altal hasznalt forras es cel objektumok
    private $_fromObject;
    private $_toObject;

    // Ugyanazt tartalmazza, mint az Entity, csak a property -k, "_" prefix nelkul vannak, igy "ORM-kompatibilis"
    private $_stdObject;

    public function __construct($id = null)
    {
        $entity         = $this->getEntityName();
        $modelClass     = 'Model_' . $entity;

        $this->_model   = new $modelClass($id);
    }

    /**
     * @return ORM
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @param ORM $model
     */
    public function setModel(ORM $model)
    {
        $this->_model = $model;
        $this->mapModelToThis();
    }

    /**
     * Entity mentese elore beallitott ertetkekbol. Betolti az ertekeit a Modelbe es elmenti azt.
     *
     * @return bool     true, ha sikerult a muvelet
     */
    public function save()
    {
        try {
            $result = true;
            Model_Database::trans_start();

            $this->mapThisToModel();
            $this->_model->save();

            $this->setPrimaryKeyFromModel();
        } catch (Exception $ex) {
            $result = false;
            Log::instance()->add(Log::ERROR, $ex->getMessage() . ' Trace: ' . $ex->getTraceAsString());
        } finally {
            Model_Database::trans_end([$result]);
        }

        return $result;
    }

    /**
     * Entity mentese kapott adatokbol. Elmenti a Modelt, es visszatolti az Entity -be
     *
     * @return bool     true, ha sikerult a muvelet
     */
    public function submit(array $data)
    {
        try {
            $result = true;
            Model_Database::trans_start();

            $this->_model->submit($data);
            $this->mapModelToThis();
        } catch (Exception $ex) {
            $result = false;
            Log::instance()->add(Log::ERROR, $ex->getMessage() . ' Trace: ' . $ex->getTraceAsString());
        } finally {
            Model_Database::trans_end([$result]);
        }

        return $result;
    }

    protected function getEntityName()
    {
        $class = get_class($this);
        $parts = explode('_', $class);

        return Arr::get($parts, 1, '');
    }

    protected function mapModelToThis()
    {
        $this->_fromObject  = $this->_model;
        $this->_toObject    = $this;

        $this->map();
    }

    protected function mapThisToModel()
    {
        $this->_fromObject  = $this;
        $this->_toObject    = $this->_model;

        $this->map();
    }

    /**
     * _fromObject adattagjait masolja _toObject -be
     * @return bool
     */
    protected function map()
    {
        try {
            $result = true;
            $this->mapProperties();

        } catch (Exception $ex) {
            Log::instance()->add(Log::ERROR, $ex->getMessage() . ' Trace: ' . $ex->getTraceAsString());
            $result = false;
        }

        return $result;
    }

    protected function mapProperties()
    {
        $this->validateFromAndToObjects();

        $fromClass      = get_class($this->_fromObject);

        // Jeloli, hogy a from vagy a to objektum Entity tipus
        $isFromEntity   = (stripos($fromClass, 'Entity') === false) ? false : true;
        $isToEntity     = !$isFromEntity;

        $realFrom       = ($isFromEntity) ? $this->mapThisToStdObject() : $this->_fromObject->object();
        $prefix         = ($isToEntity) ? '_' : '';

        foreach ($realFrom as $key => $value) {
            $this->setProperty($prefix . $key, $value);
        }
    }

    protected function setProperty($key, $value)
    {
        $this->_toObject->{$key} = $value;
    }

    protected function validateFromAndToObjects()
    {
        if (!is_object($this->_fromObject) || !is_object($this->_toObject)) {
            throw new Exception('Trying to map non-object value');
        }

        return true;
    }

    /**
     * Visszaad egy stdClass -t, ami az Entity addattagjait tartlmazza "ORM-kompatibilis" modon.
     *
     * Az Entity addattagjai "_" jellel vannak prefixalva, pl.: $_name, ezeket kell eltavolitani.
     * Igy egy olyan stdClass peldanyt ad vissza ahol name nevu adattag lesz.
     *
     * @return stdClass     Ugyanazokat az adattagokat es ertekeket tartalmazza, mint a $this, csak "_" prefix nelkul
     */
    protected function mapThisToStdObject()
    {
        $entityStd = new stdClass();

        // Vegmegy az osszes adattagon es hozzarendeli egy stdClass -hez
        foreach ($this as $key => $value) {
            $this->mapOnePropertyToStdObject($entityStd, $key, $value);
        }

        return $entityStd;
    }

    /**
     * Ellenorzi es hozzarendeli a kapott stdClass -hez a $key addattagot a $value ertekkel
     *
     * @param $entityStd    stdClass peldany, amibe le lesz masolva az Entity
     * @param $key          Adattag neve
     * @param $value        Adattag erteke
     */
    protected function mapOnePropertyToStdObject($entityStd, $key, $value)
    {
        // Csak akkor, ha megengedett
        if (!in_array($key, self::$_disabledPropertiesInMap)) {
            $this->setStdObjectUnprefixedProperty($entityStd, $key, $value);
        }
    }

    /**
     * Beallitja a kapott stdClass peldany $key adattagjat $value ertekre. Elotte lekeri a $key -hez tartozo
     * prefix nelkuli nevet. Pl.: _project_id eseten project_id
     *
     * @param $entityStd    stdClass peldany, amibe le lesz masolva az Entity
     * @param $key          Adattag neve
     * @param $value        Adattag erteke
     */
    protected function setStdObjectUnprefixedProperty($entityStd, $key, $value)
    {
        $unprefixedName                 = $this->getUnprefixedPropertyName($key);
        $entityStd->{$unprefixedName}   = $value;
    }

    /**
     * Visszaadja a "_" jellel prefixalt adattag prefix nelkuli nevet
     *
     * @param $prefixedName
     * @return string
     */
    protected function getUnprefixedPropertyName($prefixedName)
    {
        $unprefixedName = $prefixedName;

        // Ha tenlyeg '_' prefixalt
        if (substr($prefixedName, 0, 1) == '_') {
            // Levagja az elso karaktert
            $unprefixedName = substr($prefixedName, 1);
        }

        return $unprefixedName;
    }

    /**
     * Beallitja az elsodleges kulcs erteket Model alapjan
     */
    protected function setPrimaryKeyFromModel()
    {
        // Elsodleges kulcs ertekenek betoltese
        $primaryKey         = $this->_model->primary_key();
        $prefixed           = '_' . $primaryKey;
        $this->{$prefixed}  = $this->_model->pk();
    }
}