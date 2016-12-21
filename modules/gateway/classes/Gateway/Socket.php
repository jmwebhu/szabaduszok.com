<?php

abstract class Gateway_Socket 
{
    /**
     * @var Kohana_Config
     */
    protected $_config;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->_config = Kohana::$config->load('socket');
    }
    
    abstract public function signal();

    /**
     * @return string
     */
    abstract protected function getUri();

    protected function sendPost($data, $action = '')
    {
        $options = [
            'http' => [ 
                'header'  => "Content-type: application/x-www-form-urlencoded; charset=UTF-8\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $content = @file_get_contents(
            $this->_config->get('url') . $this->getUri() . '/' . $action, false, $context);

        if (is_bool($content) && $content == false) {
            Log::instance()->add(Log::ERROR, 'SOCKET URI: ' . $this->getUri() . ' ACTION: ' . $action . ' DATA: ' . json_encode($data));
            return false;
        }
    }
    
}