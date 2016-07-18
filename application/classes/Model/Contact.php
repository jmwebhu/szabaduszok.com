<?php

class Model_Contact  extends ORM
{
	protected $_created_column = [
		'column' => 'created_at',
		'format' => 'Y-m-d H:i'
	];
	
	protected $_table_columns = [
		'contact_id'	=> ['type' => 'int', 'key' => 'PRI'],
		'user_id'		=> ['type' => 'int', 'null' => true],
		'message'		=> ['type' => 'string', 'null' => true],
		'email'			=> ['type' => 'string', 'null' => true],
		'created_at'	=> ['type' => 'datetime', 'null' => true],
	];
	
    /**
     * @var $_table_name ORM -hez tartozo tabla neve
     */
    protected $_table_name = 'contact';

    /**
     * @var $_primary_key Elsodleges kulcs a tablaban
     */
    protected $_primary_key = 'contact_id';
    
    public function submit(array $data)
    {
    	$loggedUser = Auth::instance()->get_user();
    	
    	if (!$loggedUser->loaded())
    	{
    		$this->user_id = $loggedUser->user_id;
    	}
    	
    	$submit = parent::submit($data);
    	
    	$message = Arr::get($data, 'name') . ' - ' . Arr::get($data, 'email') . "\r\n" . Arr::get($data, 'message') . "\r\n" . 'contact_id: ' . $this->contact_id; 
    	
    	Email::send('hello@szabaduszok.com', '[ÚJ VÉLEMÉNY]', $message);
    	
    	return $submit;
    }
    
}