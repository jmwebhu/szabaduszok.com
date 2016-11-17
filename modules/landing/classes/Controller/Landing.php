<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Landing extends Controller_DefaultTemplate
{
	public function action_ajax()
	{
		try
    	{
    		Model_Database::trans_start();
    		$result = ['error' => false];
    		
	    	if (!$this->request->is_ajax())
	    	{
	    		throw new HTTP_Exception_400('Only Ajax');
	    	}
	    	
	    	$this->auto_render = false;
			
	        switch ($this->request->param('actiontarget'))
	        {
	        	case 'open':
	        		$landingModel 	= new Model_Landing();
	        		$landing 		= $landingModel->where('name', '=', Input::post('name'))->find();

	        		$json = json_encode($landing->open(Input::post('name')));
	        			
	        		break;	                	            
	                
	            default:
	            	throw new HTTP_Exception_400('Action target not found');
			}
		}
		catch (Exception $ex)		// Altalanos hiba
		{
			Log::instance()->addException($ex);
		
			$result	= ['error' => true];
			$json 	= json_encode($result);

			$this->response->status(500);
		}
		finally
		{
			Model_Database::trans_end([!Arr::get($result, 'error')]);
		}
		
		$this->response->body($json);
	}
}