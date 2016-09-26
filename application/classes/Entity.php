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
     * Hozzarendeli a betoltott Model ertekeit az Entity mezoihez
     */
    protected function mapModelToThis()
    {
        // Ha nincs betoltve, nem kell semmit csinalni
        if (!$this->_model->loaded())
        {
            return true;
        }

        // Vegmegy a Model osszes mezojen, es $this mezokhoz rendeli az ertekeket
        foreach ($this->_model as $key => $value)
        {
            // Csak akkor ha letezik
            if (property_exists($this, $key))
            {
                $this->{$key} = $value;
            }
            else    // Log bejegyzes keszitese
            {
                \Log::instance()->add(\Log::NOTICE, $key . ' not exists in class ' . get_class($this));
            }
        }
    }
}