<?php

class Controller_User_Auth extends Controller_User_Base
{
    /**
     * @var string
     */
    protected $_url;

    public function action_login()
    {
        try
        {
            $this->context->title = 'Belépés';
            $this->handleSessionError('error');
            $this->handleLogin();

        } catch (Exception_UserLogin $exul) {
            $this->context->error   = $exul->getMessage();
            $this->_error           = true;

        } catch (Exception $ex) {
            $this->context->error = __('defaultErrorMessage');
            Log::instance()->addException($ex);
            $this->_error = true;

        } finally {
           $this->handleFinally();
        }
    }

    public function action_logout()
    {
        $this->auto_render = false;
        Auth::instance()->logout(true, true);
        header('Location: ' . Route::url('home'), true, 302);
        die();
    }

    public function action_passwordreminder()
    {
        try {
            $this->context->title = 'Elfelejtett jelszó';
            $this->handlePasswordReminderPostRequest();

        } catch (Exception_UserNotFound $unfex) {
            $this->context->error = $unfex->getMessage();
            $this->_error = true;

        } catch (Exception $ex) {
            $this->context->error = __('defaultErrorMessage');
            Log::instance()->addException($ex);
            $this->_error = true;

        } finally {
            if ($this->request->method() == Request::POST) {
                Model_Database::trans_end([!$this->_error]);

                if (!$this->_error) {
                    header('Location: ' . Route::url('login'), true, 302);
                    die();
                }
            }
        }
    }

    protected function handlePasswordReminderPostRequest()
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_start();

            $user           = new Model_User();
            $result         = $user->passwordReminder(Arr::get(Input::post_all(), 'email'));
            $this->_error   = $result['error'];
        }
    }

    protected function handleLogin()
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_start();

            $user       = new Model_User();
            $this->_url = $user->login(Input::post_all());
        }
    }

    protected function handleFinally()
    {
        if ($this->request->method() == Request::POST) {
            Model_Database::trans_end([!$this->_error]);

            $this->handleRedirect();
        }
    }

    protected function handleRedirect()
    {
        if ($this->_error) {
            return false;
        }

        if (Session::instance()->get('redirect_url')) {
            Session::instance()->delete('redirect_url');
            $this->_url = Session::instance()->get('redirect_url');
        }

        header('Location: ' . $this->_url, true, 302);
        die();
    }
}