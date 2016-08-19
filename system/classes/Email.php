<?php defined('SYSPATH') OR die('No direct script access.');

class Email extends Kohana_Email 
{
    public static function send($to, $subject, $message)
    {
    	try
    	{
			//$mail	   = false;			
            $headers   = array();
            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-type: text/html; charset=UTF-8";
            $headers[] = "From: Szabaduszok.com Csapata <martin@szabaduszok.com>";
            $headers[] = "Reply-To: Szabaduszok.com Csapata <martin@szabaduszok.com>";
            $headers[] = "X-Mailer: PHP/" . phpversion();

			//if ($to)
			//{
				$mail = mail($to, $subject, $message, implode("\r\n", $headers));
				
				if (!$mail)
				{
					throw new Exception(__('emailSendError. to: ' . $to . ' subject: ' . $subject . ' message: ' . $message));
				}	    	

				if (Kohana::$environment == Kohana::DEVELOPMENT)
				{
					file_put_contents($to . '.html', $message);
				}
			//}                            		
    	}
    	catch (Exception $ex)
    	{    		    		
    		$errorLog = new Model_Errorlog();
    		$errorLog->log($ex);
    	}          

        return $mail;
    }
}
