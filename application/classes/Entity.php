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
    // Azok az adattagok, amiket nem kell self::map() -nak masolni
    private static $_disabledPropertiesInMap = ['_model', '_business', '_destinationObject', '_targetObject', '_stdObject'];

    protected $_model;
    protected $_business;

    // self::map() altal hasznalt forras es cel objektumok
    private $_destinationObject = null;
    private $_targetObject      = null;

    // Ugyanazt tartalmazza, mint az Entity, csak a property -k, "_" prefix nelkul vannak, igy "ORM-kompatibilis"
    private $_stdObject;

    public function __construct($id = null)
    {
        $entity             = $this->getEntityName();
        $modelClass         = 'Model_' . $entity;
        $businessClass      = 'Business_' . $entity;

        $this->_model       = new $modelClass($id);
        $this->_business    = new $businessClass($this->_model);
        $this->_stdObject   = new stdClass();
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
        $this->_destinationObject   = $this->_model;
        $this->_targetObject        = $this;

        $this->map();
    }

    protected function mapThisToModel()
    {
        $this->_destinationObject   = $this;
        $this->_targetObject        = $this->_model;

        $this->map();
    }

    /**
     * _destinationObject adattagjait masolja _targetObject -be
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
        $this->validateObjects();

        $destinationClass       = get_class($this->_destinationObject);

        // Jeloli, hogy a destination vagy a target objektum Entity tipus
        $isDestinationEntity    = (stripos($destinationClass, 'Entity') === false) ? false : true;
        $isTargetEntity         = !$isDestinationEntity;

        /**
         * @todo mapThis most nem ad vissza semmit, helyette $this->_stdObject -ben tarolja
         */

        $realDestination        = ($isDestinationEntity) ? $this->mapThisToStdObject() : $this->_destinationObject->object();
        $prefix                 = ($isTargetEntity) ? '_' : '';

        foreach ($realDestination as $key => $value) {
            $this->setPropertyInTarget($prefix . $key, $value);
        }
    }

    protected function setPropertyInTarget($key, $value)
    {
        $this->_targetObject->{$key} = $value;
    }

    protected function validateObjects()
    {
        if (!is_object($this->_destinationObject) || !is_object($this->_targetObject)) {
            throw new Exception('Trying to map non-object value');
        }

        return true;
    }

    protected function mapThisToStdObject()
    {
        foreach ($this as $key => $value) {
            $this->mapOnePropertyToStdObject($key, $value);
        }

        return $this->_stdObject;
    }

    protected function mapOnePropertyToStdObject($key, $value)
    {
        if (!in_array($key, self::$_disabledPropertiesInMap)) {
            $this->setStdObjectUnprefixedProperty($key, $value);
        }
    }

    protected function setStdObjectUnprefixedProperty($key, $value)
    {
        $unprefixedKey                         = $this->getUnprefixedPropertyName($key);
        $this->_stdObject->{$unprefixedKey}    = $value;
    }

    protected function getUnprefixedPropertyName($prefixedName)
    {
        $unprefixedName = $prefixedName;

        if (substr($prefixedName, 0, 1) == '_') {
            $unprefixedName = substr($prefixedName, 1);
        }

        return $unprefixedName;
    }

    protected function setPrimaryKeyFromModel()
    {
        $primaryKey         = $this->_model->primary_key();
        $prefixed           = '_' . $primaryKey;
        $this->{$prefixed}  = $this->_model->pk();
    }
}