<?php

class Controller_User_Freelancers extends Controller_User_Base
{
    /**
     * @var Pager
     */
    private $_pager;

    /**
     * @var int
     */
    private $_offset;

    /**
     * @var array of Model_User
     */
    private $_matchedUsers;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_user = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
    }

    protected function setPagerByRequest()
    {
        $limit 			= Kohana::$config->load('users')->get('pagerLimit');
        $currentPage 	= $this->request->param('page') ? $this->request->param('page') : 1;
        $this->_offset  = (($currentPage - 1) * $limit);

        $this->_pager = new Pager(
            AB::select()->from(new Model_User())->where('type', '=', Entity_User::TYPE_FREELANCER)->where('is_active', '=', 1)->execute()->as_array(),
            $currentPage,
            $limit,
            Route::url('freelancers')
        );
    }

    protected function handleRequest()
    {
        if ($this->request->method() == Request::POST) {
            $this->handlePostRequest();
        } else {
            $this->handleGetRequest();
        }
    }

    protected function handlePostRequest()
    {
        $this->context->needPager = false;

        if (Input::post('complex')) {
            $this->handleComplexSearch();
        } else {
            $this->setContainerToSimpleSearch();
        }

        $this->_user->setSearch(Search_Factory_User::makeSearch(Input::post_all()));
        $this->_matchedUsers = $this->_user->search();
    }

    protected function handleComplexSearch()
    {
        $postProfessionIds	= Input::post('professions', []);
        $postSkillIds 		= Input::post('skills', []);

        $profession	        = new Model_Profession();
        $skill 		        = new Model_Skill();

        $postProfessions	= $profession->getModelsByIds($postProfessionIds);
        $postSkills 		= $skill->getModelsByIds($postSkillIds);

        $this->setContainerToComplexSearch($postProfessions, $postSkills);
    }

    /**
     * @param array $professions
     * @param array $skills
     */
    protected function setContainerToComplexSearch(array $professions, array $skills)
    {
        $industry = new Model_Industry();
        $this->context->container = Search_View_Container_Factory_User::createContainer([
            'selectedIndustryIds'   => Input::post('industries', []),
            'professions'           => $professions,
            'skills'                => $skills,
            'skill_relation'        => Input::post('skill_relation', 1),
            'current'               => 'complex',
            'industries'            => $industry->getAll(),
        ]);
    }

    protected function setContainerToSimpleSearch()
    {
        $this->context->container = Search_View_Container_Factory_User::createContainer([
            'search_term'   => Input::post('search_term'),
            'current'       => 'simple'
        ]);
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

        $this->_matchedUsers   = AB::select()
            ->from(Arr::merge($withPicture, $withoutPicture))
            ->where('user_id', '!=', '')
            ->limit($this->_pager->getLimit())
            ->offset($this->_offset)
            ->execute()->as_array();
    }

    protected function setContext()
    {
        $this->context->title 		= $this->_user->getCount() . ' Szabadúszó egy helyen';
        $this->context->users	    = $this->_user->getEntitiesFromModels($this->_matchedUsers);

        $this->setContextPager();
        $industry = new Model_Industry();

        if (!isset($this->context->container)) {
            $this->context->container = Search_View_Container_Factory_User::createContainer(['current' => 'complex', 'industries' => $industry->getAll()]);
        }
    }

    protected function setContextPager()
    {
        $this->context->pager               = $this->_pager;
        $this->context->countProjects		= count($this->_matchedUsers);
        $this->context->countAllProjects	= $this->_user->getCount();
    }

    public function action_index()
    {
        try
        {
            $this->setPagerByRequest();
            $this->handleRequest();
            $this->setContext();

        } catch (HTTP_Exception_403 $exforbidden) {
            $exforbidden->setRedirectRoute($this->request->route());
            $exforbidden->setRedirectSlug($this->request->param('slug'));

            Session::instance()->set('error', $exforbidden->getMessage());
            $this->defaultExceptionRedirect($exforbidden);

        } catch (Exception $ex) {
            Log::instance()->addException($ex);
        }
    }
}