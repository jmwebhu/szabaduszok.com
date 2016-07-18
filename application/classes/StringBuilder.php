<?php

/**
 * class StringBuilder
 * 
 * Simple StringBuilder class
 * 
 * @author      Joó Martin <joomartin@jmweb.hu>
 * @package     Helpers
 * @version     1.0
 */

class StringBuilder
{
    /**
     * @var string  Concaterated string
     */
    protected $_string;
    
    /**
     * Constructor
     * 
     * @param null|string $string    Init string
     * @return StringBuilder 
     */
    public function __construct($string = null) 
    {
        $this->_string = $string;
        
        return $this;
    }
    
    /**
     * Appends new string
     * 
     * @param string $string
     * @return StringBuilder
     */
    public function append($string)
    {
        $this->_string .= $string;
        
        return $this;
    }
    
    /**
     * Retunrs the concaterated string
     * 
     * @param null|string $default      Default value, if string is empty
     * @return string
     */
    public function get($default = null)
    {
        return (!$this->_string) ? $default : $this->_string;
    }
}