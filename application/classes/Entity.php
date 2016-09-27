<?php

/**
 * Class Entity
 *
 * Alap Entity osztaly, minden konkret Entity ebbol orokol
 *
 * Az Entity egyfajta Facade -kent mukodik, magaban foglalja az ORM, BO es egyeb szukseges osztalyokat.
 * A kliensek vtole fuggnek
 *
 * @author Joo Martin
 */

abstract class Entity
{
    /**
     * Entity -hez tartozo ORM peldany
     * @var ORM
     */
    protected $_model;

    /**
     * Entity constructor.
     */
    public function __construct($id = null)
    {
        $entity     = $this->getEntityName();
        $modelClass = 'Model_' . $entity;

        $this->_model       = new $modelClass($id);
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

    /**
     * Visszaadja az Entity nevet, pl Entity_Project eseten "Project"
     *
     * @return String
     */
    protected function getEntityName()
    {
        $class = get_class($this);
        $parts = explode('_', $class);

        return Arr::get($parts, 1, '');
    }

    /**
     * Hozzarendeli a Model adattagjait az Entity adattagjaihoz
     *
     * @uses Entity::map()
     */
    protected function mapModelToThis()
    {
        $this->map($this->_model, $this);
    }

    /**
     * Hozzarendeli az Entity adattagjait a Model adattagjaihoz
     *
     * @uses Entity::map()
     */
    protected function mapThisToModel()
    {
        $this->map($this, $this->_model);
    }

    /**
     * A $from objektum adattagjait hozzarendeli a $to objektum adattagjaihoz
     *
     * @param $from     Forras objektum
     * @param $to       Cel objektum
     *
     * @return boolean  false, ha valamelyik parameter nem megfelelo.
     */
    protected function map($from, $to)
    {
        try {
            // Mindket paramter valid
            if ($this->validateObjects($from, $to)) {
                $this->mapProperties($from, $to);
            }
        }
        catch (Exception $ex) {
            Log::instance()->add(Log::ERROR, $ex->getMessage() . ' Trace: ' . $ex->getTraceAsString());
            $result = false;
        }

        return true;
    }

    /**
     * Vizsgalja a kapott objektumok tipusat, majd vegmegy az adattagjain es kitolti oket
     *
     * @param $from     Forras objektum
     * @param $to       Cel objektum
     */
    protected function mapProperties($from, $to)
    {
        $fromClass      = get_class($from);

        // Jeloli, hogy a from vagy a to objektum Entity tipus
        $isFromEntity   = (stripos($fromClass, 'Entity') === false) ? false : true;
        $isToEntity     = !$isFromEntity;

        // Ha Entity, akkor lekerdezi a stdObject -et (ahol nincsenek "_" prefixek az adattagok elott)
        // Ha ORM, akkor array -e alakitja
        $realFrom       = ($isFromEntity) ? $this->mapThisToStdObject() : $from->object();
        $prefix         = ($isToEntity) ? '_' : '';

        // Vegmegy az osszes mezojen, es a $to mezokhoz rendeli az ertekeket
        foreach ($realFrom as $key => $value) {
            $this->mapOneProperty($to, $key, $value, $prefix);
        }
    }

    /**
     * Beallit egyetlen adattagot a $to objektumon
     *
     * @param $to       Cel objektum
     * @param $key      Adattag kulcs
     * @param $value    Ertek
     * @param $prefix   ADattag prefxe ('_')
     */
    protected function mapOneProperty($to, $key, $value, $prefix)
    {
        $fullKey        = $prefix . $key;
        $to->{$fullKey} = $value;
    }

    /**
     * Validalja a kapott parametereket. Mindketto objektum kell legyen
     *
     * @param mixed $from
     * @param mixed $to
     * @return bool
     */
    protected function validateObjects($from, $to)
    {
        // Csak objektumok lehetnek a parameterek
        if (!is_object($from) || !is_object($to)) {
            Log::instance()->add(Log::NOTICE, 'Trying to map non-object value');
            return false;
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
        $disabledProperties = ['_model'];

        // Csak akkor, ha megengedett
        if (!in_array($key, $disabledProperties)) {
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
        $unprefixedName = $this->getUnprefixedPropertyName($key);
        $entityStd->{$unprefixedName} = $value;
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