<?php

class Controller_Cron extends Controller
{
	public function after()
	{
		$this->auto_render = false;
		$this->response->headers('Content-Type', 'application/json');
	}
	
	public function action_send_project_notification()
	{
		try
		{
			Model_Database::trans_start();
			
			$notification = new Model_Project_Notification();
            $notification->send();
			
			$result = ['error' => false, 'status' => 200];
		}
		catch (Exception $ex)		// Altalanos hiba
		{
			Log::instance()->addException($ex);
		
			$result = ['error' => true, 'status' => 500];
		}
		finally
		{
			Model_Database::trans_end([!Arr::get($result, 'error')]);
			
			$this->response->body(json_encode($result));
			$this->response->status(Arr::get($result, 'status', 200));
		}				
	}
}