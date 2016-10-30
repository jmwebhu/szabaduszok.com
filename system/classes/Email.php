<?php defined('SYSPATH') OR die('No direct script access.');

class Email extends Kohana_Email 
{
    /**
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public static function send($to, $subject, $message)
    {
    	try {
            $mail = true;
            $headers   = array();
            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-type: text/html; charset=UTF-8";
            $headers[] = "From: Martin Szabaduszok.com <martin@szabaduszok.com>";
            $headers[] = "Reply-To: Martin Szabaduszok.com <martin@szabaduszok.com>";
            $headers[] = "X-Mailer: PHP/" . phpversion();

            if ($to) {
                if (Kohana::$environment == Kohana::DEVELOPMENT) {
                    file_put_contents($to . '.html', $message);
                    return true;
                }

                $mail = mail($to, $subject, $message, implode("\r\n", $headers));

                if (!$mail) {
                    throw new Exception('E-mail hiba. to: ' . $to . ' subject: ' . $subject . ' message: ' . $message);
                }
            }
    	} catch (Exception $ex) {
    	    Log::instance()->addException($ex);
            $mail = false;
    	}          

        return $mail;
    }
}
