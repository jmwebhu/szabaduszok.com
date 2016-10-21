<?php

/**
 * Class Controller_Project_List
 *
 * Felelosseg: Projekt lista keres kiszolgalasa
 */
class Controller_Project_List extends Controller_List
{
    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_entity = new Entity_Project();
    }

    protected function doSearch()
    {
        $this->_entity->setSearch(Search_Factory_Project::makeSearch(Input::post_all()));
        $this->_matchedModels = $this->_entity->search();
    }

    /**
     * @return array
     */
    protected function getInitModels()
    {
        return AB::select()->from(new Model_Project())->where('is_active', '=', 1)->execute()->as_array();
    }

    /**
     * @return string
     */
    protected function getPagerUrl()
    {
        return Route::url('projectList');
    }

    /**
     * @return string
     */
    protected function getSearchContainerFactoryClass()
    {
        return Search_View_Container_Factory_Project::class;
    }

    public function action_index()
    {
        try {
            $this->tryBody();

        } catch (Exception $ex) {
            Log::instance()->addException($ex);
        }
    }

    protected function handleGetRequest()
    {
        $this->context->needPager       = true;
        $this->_matchedModels = $this->_entity->getOrderedAndLimited($this->_pager->getLimit(), $this->_offset);
    }

    protected function setContext()
    {
        $this->setContextRelations();

        $this->context->title 		= 'Szabadúszó projektek, munkák';
        $this->context->projects	= $this->_entity->getEntitiesFromModels($this->_matchedModels);

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

        foreach ($this->_matchedModels as $project) {
            /**
             * @var $project Model_Project
             */
            $relations[$project->project_id]    = $project->getRelations();
            $salaries[$project->project_id]     = Viewhelper_Project::getSalary(new Entity_Project($project));
            $users[$project->project_id]		= Arr::get($cacheUsers, $project->user_id);
        }

        $entity = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        return [
            'relations'     => $relations,
            'salaries'      => $salaries,
            'users'         => $entity->getEntitiesFromModels($users)
        ];
    }

    protected function setContextPager()
    {
        $this->context->pager               = $this->_pager;
        $this->context->countProjects		= count($this->_matchedModels);
        $this->context->countAllProjects	= $this->_entity->getCount();
    }
}