<?php

/**
 * Class Controller_Project_List
 *
 * Felelosseg: Projekt lista keres kiszolgalasa
 */
class Controller_Project_List extends Controller_Project
{
    private $_matchedProjects   = [];
    private $_needPager         = false;
    private $_offset;

    /**
     * @var Pager
     */
    private $_pager;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function action_index()
    {
        try {
            $this->setPagerByRequest();
            $this->handleRequest();
            $this->setContext();

        } catch (Exception $ex) {
            Log::instance()->addException($ex);
        }
    }

    protected function setPagerByRequest()
    {
        $limit 			= Kohana::$config->load('projects')->get('pagerLimit');
        $currentPage 	= $this->request->param('page') ? $this->request->param('page') : 1;

        $this->_pager = new Pager(
            AB::select()->from(new Model_Project())->where('is_active', '=', 1)->execute()->as_array(),
            $currentPage,
            $limit,
            URL::base(null, true) . 'szabaduszo-projektek/'
        );

        $this->_offset = (($currentPage - 1) * $limit);
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
        $this->_needPager = false;

        if (Input::post('complex')) {
            $this->handleComplexSearch();
        } else {
            $this->setContainerToSimpleSearch();
        }

        $this->_project->setSearch(Search_Factory_Project::makeSearch(Input::post_all()));
        $this->_matchedProjects = $this->_project->search();
    }

    protected function handleComplexSearch()
    {
        $postProfessionIds  = Input::post('professions', []);
        $postSkillIds       = Input::post('skills', []);

        $profession	        = new Model_Profession();
        $skill 		        = new Model_Skill();

        $postProfessions	= $profession->getModelsByIds($postProfessionIds);
        $postSkills 		= $skill->getModelsByIds($postSkillIds);

        $this->setContainerToComplexSearch($postProfessions, $postSkills);
    }

    protected function setContainerToSimpleSearch()
    {
        $this->context->container = Search_View_Container_Factory_Project::createContainer([
            'search_term'   => Input::post('search_term'),
            'current'       => 'simple'
        ]);
    }

    protected function handleGetRequest()
    {
        $this->_needPager       = true;
        $this->_matchedProjects = $this->_project->getOrderedAndLimited($this->_pager->getLimit(), $this->_offset);
    }

    protected function setContainerToComplexSearch(array $postProfessions, array $postSkills)
    {
        $industry = new Model_Industry();
        $this->context->container = Search_View_Container_Factory_Project::createContainer([
            'selectedIndustryIds'   => Input::post('industries', []),
            'professions'           => $postProfessions,
            'skills'                => $postSkills,
            'skill_relation'        => Input::post('skill_relation', 1),
            'current'               => 'complex',
            'industries'            => $industry->getAll(),
        ]);
    }

    protected function setContext()
    {
        $this->setContextRelations();

        $this->context->title 		= 'Szabadúszó projektek, munkák';
        $this->context->projects	= $this->_project->getEntitiesFromModels($this->_matchedProjects);
        $this->context->needPager   = $this->_needPager;

        $this->setContextPager();
    }

    protected function setContextRelations()
    {
        $data       = $this->getRelationData();
        $industry	= new Model_Industry();

        $this->context->relations	= $data['relations'];
        $this->context->salaries	= $data['salaries'];
        $this->context->users		= $data['users'];

        if (!isset($this->context->container)) {
            $this->context->container = Search_View_Container_Factory_Project::createContainer(['current' => 'complex', 'industries' => $industry->getAll()]);
        }
    }

    protected function getRelationData()
    {
        $user		= new Model_User();

        $relations		= [];
        $salaries		= [];
        $users 			= [];
        $cacheUsers		= $user->getAll();

        foreach ($this->_matchedProjects as $project) {
            /**
             * @var $project Model_Project
             */
            $relations[$project->project_id]    = $project->getRelations();
            $salaries[$project->project_id]     = Viewhelper_Project::getSalary(new Entity_Project($project->project_id));
            $users[$project->project_id]		= Arr::get($cacheUsers, $project->user_id);
        }

        return [
            'relations'     => $relations,
            'salaries'      => $salaries,
            'users'         => $users
        ];
    }

    protected function setContextPager()
    {
        $this->context->pager = $this->_pager;

        $this->context->countProjects		= count($this->_matchedProjects);
        $this->context->countAllProjects	= $this->_project->getCount();
    }
}