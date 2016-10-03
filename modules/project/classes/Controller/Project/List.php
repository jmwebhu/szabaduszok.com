<?php

class Controller_Project_List extends Controller_DefaultTemplate
{
    /**
     * @var Entity_Project
     */
    private $_project;
    private $_matchedProjects   = [];
    private $_pagerData         = [];
    private $_needPager         = false;

    public function __construct(Request $request, Response $response)
    {
        $this->_project = new Entity_Project();
        parent::__construct($request, $response);
    }

    public function action_index()
    {
        try {
            $this->setPagerData();

            if ($this->request->method() == Request::POST) {
                $this->handlePostRequest();
            } else {
                $this->handleGetRequest();
            }

            $this->setContext();
        } catch (Exception $ex) {
            Log::instance()->addException($ex);
        }
    }

    protected function setPagerData()
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
        $this->_matchedProjects = $this->_project->search(Input::post_all());
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
        $user		= new Model_User();
        $industry	= new Model_Industry();

        $relations		= [];
        $salaries		= [];
        $users 			= [];
        $cacheUsers		= $user->getAll();

        foreach ($this->_matchedProjects as $project)
        {
            /**
             * @var $project Model_Project
             */
            $relations[$project->project_id]    = $project->getRelations();
            $salaries[$project->project_id]     = Viewhelper_Project::getSalary($project);
            $users[$project->project_id]		= Arr::get($cacheUsers, $project->user_id);
        }

        $this->context->relations	= $relations;
        $this->context->salaries	= $salaries;
        $this->context->users		= $users;
        $this->context->industries	= $industry->getAll();
        $this->context->title 		= 'Szabadúszó projektek, munkák';

        $this->context->projects	= $this->_matchedProjects;
        $this->context->needPager   = $this->_needPager;

        $this->setContextToPager();
    }

    protected function setContextToPager()
    {
        /**
         * @var $pagesCountFloat				Lapok szama tizedes szamban. Projektek szama / limit. Pl.: 33 / 10 = 3.3
         * @var $pagesCountInt				    Lapok szama egesz szamban. Pl. 3.3 => 3
         * @var $pagesCountDecimalRemainder	    A ket ertek kulonbsege, tehat a tizedes maradek. Pl.: 3.3 - 3 = 0.3
         *
         * Ha van maradek, akkor egyel tobb lap van
         */
        $pagesCountFloat            = $this->_project->getCount() / $this->_pagerData['limit']; // Pl.: 33 / 10 = 3.3
        $pagesCountInt 			    = floor($pagesCountFloat);				                    // 3.3 => 3
        $pagesCountDecimalRemainder	= $pagesCountFloat - $pagesCountInt;		                // 3.3 - 3 = 0.3

        // Ha van tizedes maradek, akkor egyel tobb lap kell
        if ($pagesCountDecimalRemainder != 0) {
            $pagesCountInt++;
        }

        $pages = [];
        for ($i = 1; $i <= $pagesCountInt; $i++) {
            $pages[] = $i;
        }

        $this->context->pagesCount			= $pagesCountInt;
        $this->context->pages				= $pages;
        $this->context->currentPage			= $this->_pagerData['currentPage'];
        $this->context->countProjects		= count($this->_matchedProjects);
        $this->context->countAllProjects	= $this->_project->getCount();

        $nextPage = $this->_pagerData['currentPage'] + 1;
        $prevPage = $this->_pagerData['currentPage'] - 1;

        if ($this->_pagerData['currentPage'] == $pagesCountInt) {
            $nextPage = $pagesCountInt;
        }

        if ($this->_pagerData['currentPage'] == 1) {
            $prevPage = 1;
        }

        $this->context->nextPage = $nextPage;
        $this->context->prevPage = $prevPage;
    }
}