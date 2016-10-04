<?php

class Controller_Project_List extends Controller_Project
{
    private $_matchedProjects   = [];
    private $_pagerData         = [];
    private $_needPager         = false;
    private $_pagesCount;

    /**
     * @param array $pagerData
     */
    public function setPagerData($pagerData)
    {
        $this->_pagerData = $pagerData;
    }

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
            $this->setPagerDataByRequest();
            $this->handleRequest();
            $this->setContext();

        } catch (Exception $ex) {
            Log::instance()->addException($ex);
        }
    }

    protected function handleRequest()
    {
        if ($this->request->method() == Request::POST) {
            $this->handlePostRequest();
        } else {
            $this->handleGetRequest();
        }
    }

    protected function setPagerDataByRequest()
    {
        $limit 			= Kohana::$config->load('projects')->get('pagerLimit');
        $currentPage 	= $this->request->param('page') ? $this->request->param('page') : 1;

        $this->_pagerData = [
            'limit'         => $limit,
            'currentPage'   => $currentPage,
            'offset'        => (($currentPage - 1) * $limit)
        ];
    }

    protected function handlePostRequest()
    {
        $this->_needPager = false;

        if (Input::post('complex')) {
            $this->handleComplexSearch();
        } else {
            $this->setContextToSimpleSearch();
        }

        $this->_project->setSearch(Project_Search_Factory::makeSearch(Input::post_all()));
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

        $this->setContextToComplexSearch($postProfessions, $postSkills);
    }

    protected function setContextToSimpleSearch()
    {
        $this->context->searchTerm			= Input::post('search_term');
        $this->context->current				= 'simple';
    }

    protected function handleGetRequest()
    {
        $this->_needPager       = true;
        $this->_matchedProjects = $this->_project->getOrderedAndLimited($this->_pagerData['limit'], $this->_pagerData['offset']);
    }

    protected function setContextToComplexSearch(array $postProfessions, array $postSkills)
    {
        $this->context->postIndustries 		= Input::post('industries');
        $this->context->postProfessions 	= $postProfessions;
        $this->context->postSkills 			= $postSkills;
        $this->context->postSkillRelation	= Input::post('skill_relation', 1);
        $this->context->current				= 'complex';
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
        $this->context->industries	= $industry->getAll();
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
        $this->setPagesCount();
        $this->setContextPages();

        $this->context->pagesCount			= $this->_pagesCount;
        $this->context->currentPage			= $this->_pagerData['currentPage'];
        $this->context->countProjects		= count($this->_matchedProjects);
        $this->context->countAllProjects	= $this->_project->getCount();

        $this->setContextPrevNextPage();
    }

    protected function setPagesCount()
    {
        $pagesCountFloat            = $this->_project->getCount() / $this->_pagerData['limit']; // Pl.: 33 / 10 = 3.3
        $pagesCountInt 			    = floor($pagesCountFloat);				                    // 3.3 => 3
        $pagesCountDecimalRemainder	= $pagesCountFloat - $pagesCountInt;		                // 3.3 - 3 = 0.3

        // Ha van tizedes maradek, akkor egyel tobb lap kell
        if ($pagesCountDecimalRemainder != 0) {
            $pagesCountInt++;
        }

        $this->_pagesCount = $pagesCountInt;
    }

    protected function setContextPages()
    {
        $pages = [];
        for ($i = 1; $i <= $this->_pagesCount; $i++) {
            $pages[] = $i;
        }

        $this->context->pages = $pages;
    }

    protected function setContextPrevNextPage()
    {
        $this->context->nextPage = $this->getNextPage();
        $this->context->prevPage = $this->getPrevPage();
    }

    /**
     * @return int
     */
    protected function getNextPage()
    {
        if ($this->_pagerData['currentPage'] == $this->_pagesCount) {
            return $this->_pagesCount;
        }

        return $this->_pagerData['currentPage'] + 1;
    }

    /**
     * @return int
     */
    protected function getPrevPage()
    {
        if ($this->_pagerData['currentPage'] == 1) {
            return 1;
        }

        return $this->_pagerData['currentPage'] - 1;
    }
}