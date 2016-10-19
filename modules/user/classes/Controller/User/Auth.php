<?php

class Controller_User_Auth extends Controller_User_Base
{
    public function action_login()
    {
        try
        {
            $this->context->title = 'Belépés';
            $this->handleSessionError('error');

            // Vannak POST adatok, tehat belepes tortenik
            if ($this->request->method() == Request::POST)
            {
                Model_Database::trans_start();

                // POST adatok
                $post = Input::post_all();

                $user = new Model_User();
                $url = $user->login($post);
            }
        }
        catch (Exception_UserLogin $exul)		// Belepesi hiba
        {
            // Visszaadja a view -nak
            $this->context->error = $exul->getMessage();

            $this->_error = true;
        }
        catch (Exception $ex)		// Altalanos hiba
        {
            $this->context->error = __('defaultErrorMessage');

            // Logbejegyzest keszit
            $errorLog = new Model_Errorlog();
            $errorLog->log($ex);

            $result = ['error' => true];
        }
        finally
        {
            if ($this->request->method() == Request::POST)
            {
                Model_Database::trans_end([!Arr::get($result, 'error')]);

                // Sikeres regisztracio eseten
                if (!Arr::get($result, 'error'))
                {
                    $redirectUrl = Session::instance()->get('redirect_url');
                    if ($redirectUrl)
                    {
                        Session::instance()->delete('redirect_url');
                        $url = $redirectUrl;
                    }

                    // Atiranyitas a megfelelo url -re
                    header('Location: ' . $url, true, 302);
                    die();
                }
            }
        }
    }
}