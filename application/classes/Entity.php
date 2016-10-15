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
    /**
     * @var array Azok az adattagok, amiket nem kell self::map() -nak masolni
     */
    private static $_disabledPropertiesInMap = ['_model', '_business', '_destinationObject', '_targetObject', '_stdObject', '_file', '_search'];

    /**
     * @var ORM
     */
    protected $_model;

    /**
     * @var Business
     */
    protected $_business;

    /**
     * @var stdClass Ugyanazt tartalmazza, mint az Entity, csak a property -k, "_" prefix nelkul vannak, igy "ORM-kompatibilis"
     */
    protected $_stdObject;

    /**
     * @var Object
     */
    private $_destinationObject = null;
    /**
     * @var Object
     */
    private $_targetObject      = null;

    /**
     * @param null|int $id
     */
    public function __construct($id = null)
    {
        $entity             = $this->getEntityName();
        $modelClass         = 'Model_' . $entity;
        $businessClass      = 'Business_' . $entity;

        $this->_model       = new $modelClass($id);
        $this->_business    = new $businessClass($this->_model);
        $this->_stdObject   = new stdClass();

        if ($id) {
            $this->mapModelToThis();
        }
    }

    /**
     * @return ORM
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->_business;
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
     * @return bool
     */
    public function loaded()
    {
        return $this->_model->loaded();
    }

    /**
     * @return bool
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
            Log::instance()->addException($ex);
        } finally {
            Model_Database::trans_end([$result]);
        }

        return $result;
    }

    /**
     * @param array $data
     * @return bool
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
            Log::instance()->addException($ex);
        } finally {
            Model_Database::trans_end([$result]);
        }

        return $result;
    }

    /**
     * @param string $slug
     * @return Entity
     */
    public function getBySlug($slug)
    {
        $this->_model = $this->_model->getBySlug($slug);
        $this->mapModelToThis();

        return $this;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getRelation($name)
    {
        return $this->_model->{$name}->find_all();
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        $class = get_class($this);
        $parts = explode('_', $class);

        switch (count($parts)) {
            case 2: default:
                $name = Arr::get($parts, 1, '');
                break;

            case 4:     // Mock_Entity_Project_d0f10a18
                $name = Arr::get($parts, 2, '');
                break;
        }

        return $name;
    }

    /**
     * @return bool
     */
    protected function mapModelToThis()
    {
        $this->_destinationObject   = $this->_model;
        $this->_targetObject        = $this;

        return $this->map();
    }

    /**
     * @return bool
     */
    protected function mapThisToModel()
    {
        $this->_destinationObject   = $this;
        $this->_targetObject        = $this->_model;

        return $this->map();
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
            Log::instance()->addException($ex);
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
            $fullKey = $prefix . $key;
            $this->_targetObject->{$fullKey} = $value;
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function validateObjects()
    {
        if (!is_object($this->_destinationObject) || !is_object($this->_targetObject)) {
            throw new Exception('Trying to map non-object value');
        }

        return true;
    }

    /**
     * @return stdClass
     */
    protected function mapThisToStdObject()
    {
        foreach ($this as $key => $value) {
            $this->mapOnePropertyToStdObject($key, $value);
        }

        return $this->_stdObject;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    protected function mapOnePropertyToStdObject($key, $value)
    {
        if (!in_array($key, self::$_disabledPropertiesInMap)) {
            $this->setStdObjectUnprefixedProperty($key, $value);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    protected function setStdObjectUnprefixedProperty($key, $value)
    {
        $unprefixedKey                         = $this->getUnprefixedPropertyName($key);
        $this->_stdObject->{$unprefixedKey}    = $value;
    }

    /**
     * @param string $prefixedName
     * @return string
     */
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