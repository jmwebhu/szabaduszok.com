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
     * Entity mentese. Betolti az ertekeit a Modelbe es elmenti azt.
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
     * @return boolean  false, ha valamelyik parameter nem megfelelo, vagy nem letezo adattagot akar mappelni. Mindent logol
     */
    protected function map($from, $to)
    {
        try
        {
            $result = true;

            // Csak objektumok lehetnek a parameterek
            if (!is_object($from) || !is_object($to))
            {
                Log::instance()->add(Log::NOTICE, 'Try to map non-object values');
                return false;
            }

            // Vegmegy a Model osszes mezojen, es $this mezokhoz rendeli az ertekeket
            foreach ($from as $key => $value)
            {
                // Csak akkor ha letezik
                if (property_exists($to, $key))
                {
                    $to->{$key} = $value;
                }
                else    // Log bejegyzes keszitese
                {
                    $result = false;
                    Log::instance()->add(Log::NOTICE, $key . ' not exists in class ' . get_class($this));
                }
            }
        }
        catch (Exception $ex)
        {
            Log::instance()->add(Log::ERROR, $ex->getMessage() . ' Trace: ' . $ex->getTraceAsString());
        }

        return $result;
    }
}