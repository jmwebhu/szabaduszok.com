<?php

class Project_Notification_Search_View_Container_User extends Search_View_Container
{
    /**
     * @return string
     */
    public function getSimpleSubtitle()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getHeadingText()
    {
        return 'Projekt értesítő beállításai';
    }

    /**
     * @return string
     */
    public function getComplexButtonsHtml()
    {
        return "<button class=\"btn btn-lg btn-block btn-lime-green\" id=\"save-project-notification\">
                    <span class=\"ion ion-checkmark icon-spacer icon-white\"></span>
                    Rögzít
                </button>";
    }

    /**
     * @return bool
     */
    public function needTabs()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getComplexFormAction()
    {
        return Route::url('userAjax', ['actiontarget' => 'saveProjectNotification']);
    }

    /**
     * @return string
     */
    public function getSimpleFormAction()
    {
        return '';
    }
}