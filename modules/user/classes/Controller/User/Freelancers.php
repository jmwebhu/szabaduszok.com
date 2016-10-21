<?php

class Controller_User_Freelancers extends Controller_List
{
    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_entity = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
    }

    protected function doSearch()
    {
        $this->_entity->setSearch(Search_Factory_User::makeSearch(Input::post_all()));
        $this->_matchedModels = $this->_entity->search();
    }

    /**
     * @return array
     */
    protected function getInitModels()
    {
        return AB::select()->from(new Model_User())->where('type', '=', Entity_User::TYPE_FREELANCER)->execute()->as_array();
    }

    /**
     * @return string
     */
    protected function getPagerUrl()
    {
        return Route::url('freelancers');
    }

    /**
     * @return string
     */
    protected function getSearchContainerFactoryClass()
    {
        return Search_View_Container_Factory_User::class;
    }

    public function action_index()
    {
        try
        {
            $authorization = new Authorization_User();
            $this->throwForbiddenExceptionIfNot($authorization->canSeeFreelancers(), 'Szabadúszóink megtekintéséhez, kérjük először lépj be.');

            $this->tryBody();

        } catch (HTTP_Exception_403 $exforbidden) {
            $exforbidden->setRedirectRoute($this->request->route());
            $exforbidden->setRedirectSlug($this->request->param('slug'));

            Session::instance()->set('error', $exforbidden->getMessage());
            $this->defaultExceptionRedirect($exforbidden);

        } catch (Exception $ex) {
            Log::instance()->addException($ex);
        }
    }

    protected function handleGetRequest()
    {
        $this->context->needPager	= true;

        /**
         * @todo RENDEZES ERTEKELES ALAPJAN
         */

        $withPicture	= AB::select()
            ->from(new Model_User())
            ->where('profile_picture_path', '!=', '')
            ->and_where('type', '=', Entity_User::TYPE_FREELANCER)
            ->order_by('lastname')
            ->execute()->as_array();

        $withoutPicture	= AB::select()
            ->from(new Model_User())
            ->where('profile_picture_path', '=', '')
            ->and_where('type', '=', Entity_User::TYPE_FREELANCER)
            ->order_by('lastname')
            ->execute()->as_array();

        $this->_matchedModels   = AB::select()
            ->from(Arr::merge($withPicture, $withoutPicture))
            ->where('user_id', '!=', '')
            ->limit($this->_pager->getLimit())
            ->offset($this->_offset)
            ->execute()->as_array();
    }

    protected function setContext()
    {
        $this->context->title 		= $this->_entity->getCount() . ' Szabadúszó egy helyen';
        $this->context->users	    = $this->_entity->getEntitiesFromModels($this->_matchedModels);

        $this->setContextPager();
        $industry = new Model_Industry();

        if (!isset($this->context->container)) {
            $this->context->container = Search_View_Container_Factory_User::createContainer(['current' => 'complex', 'industries' => $industry->getAll()]);
        }
    }

    protected function setContextPager()
    {
        $this->context->pager                   = $this->_pager;
        $this->context->countMatchedFreelancers	= count($this->_matchedModels);
        $this->context->countAllFreelancers	    = $this->_entity->getCount();
    }
}