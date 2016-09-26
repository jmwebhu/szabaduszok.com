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
     * Entity -hez tartozo BO peldany
     * @var BO
     */
    protected $_business;

    /**
     * Entity constructor.
     */
    public function __construct($id = null)
    {
        $entity     = $this->getEntityName();
        $modelClass = 'Model_' . $entity;
        $boClass    = 'BO_' . $entity;

        $this->_model       = new $modelClass($id);
        $this->_business    = new $boClass();
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
    }

    /**
     * @return BO
     */
    public function getBusiness()
    {
        return $this->_business;
    }

    /**
     * @param BO $business
     */
    public function setBusiness(BO $business)
    {
        $this->_business = $business;
    }

    /**
     * Entity mentese elore beallitott ertetkekbol. Betolti az ertekeit a Modelbe es elmenti azt.
     *
     * @return bool     true, ha sikerult a muvelet
     */
    public function save()
    {
        try
        {
            $result = true;
            Model_Database::trans_start();

            $this->mapThisToModel();
            $this->_model->save();

            $this->setPrimaryKeyFromModel();
        }
        catch (Exception $ex)
        {
            $result = false;
            Log::instance()->add(Log::ERROR, $ex->getMessage() . ' Trace: ' . $ex->getTraceAsString());
        }
        finally
        {
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
        try
        {
            $result = true;
            Model_Database::trans_start();

            $this->_model->submit($data);
            $this->mapModelToThis();
        }
        catch (Exception $ex)
        {
            $result = false;
            Log::instance()->add(Log::ERROR, $ex->getMessage() . ' Trace: ' . $ex->getTraceAsString());
        }
        finally
        {
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
        try
        {
            // Csak objektumok lehetnek a parameterek
            if (!is_object($from) || !is_object($to))
            {
                Log::instance()->add(Log::NOTICE, 'Trying to map non-object value');
                return false;
            }

            $fromClass      = get_class($from);

            $isFromEntity   = (stripos($fromClass, 'Entity') === false) ? false : true;
            $isToEntity     = !$isFromEntity;

            $realFrom       = ($isFromEntity) ? $this->getStdObject() : $from->object();
            $prefix         = ($isToEntity) ? '_' : '';

            // Vegmegy a Model osszes mezojen, es $this mezokhoz rendeli az ertekeket
            foreach ($realFrom as $key => $value)
            {
                $fullKey        = $prefix . $key;
                $to->{$fullKey} = $value;
            }
        }
        catch (Exception $ex)
        {
            Log::instance()->add(Log::ERROR, $ex->getMessage() . ' Trace: ' . $ex->getTraceAsString());
        }

        return true;
    }

    /**
     * Visszaad egy stdClass -t, ami az Entity addattagjait tartlmazza "ORM-kompatibilis" modon.
     *
     * Az Entity privat addattagjai "_" jellel vannak prefixalva, pl.: $_name, ezeket kell eltavolitani
     *
     * @return stdClass     Ugyanazokat az adattagokat es ertekeket tartalmazza, mint a $this, csak "_" prefix nelkul
     */
    protected function getStdObject()
    {
        $entityStd          = new stdClass();
        $disabledProperties = ['_model', '_business'];

        foreach ($this as $key => $value)
        {
            if (!in_array($key, $disabledProperties))
            {
                $unprefixedName = $this->getUnprefixedPropertyName($key);
                $entityStd->{$unprefixedName} = $value;
            }
        }

        return $entityStd;
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
        if (substr($prefixedName, 0, 1) == '_')
        {
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