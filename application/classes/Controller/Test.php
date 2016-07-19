<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller
{
    public function action_index()
    {	        
		$data = [
			['name' => 'Joó Martin', 'number' => '9', 'float' => 1.34, 'float1' => 1.543],
			['name' => 'Bognár Mariann', 'number' => 12, 'float' => 12.4, 'float1' => 2.742],
			['name' => 'Lionel Messi', 'number' => 8, 'float' => 0.9, 'float1' => 10.846],
			['name' => 'C. Ronaldo', 'number' => 7, 'float' => 31.947, 'float1' => 40.453]
		];

		$result = AB::select()->from($data)
			->and_where_open()
				->where('float', '>=', 10.43)
				->and_where('float1', '<', '5.412')
			->and_where_close()
			->execute()->as_array();
		
		echo Debug::vars($result);
    }	

    public function action_clearcache()
    {
        Cache::instance()->delete_all();
    }
}
