<?php

abstract class Controller_List extends Controller_DefaultTemplate
{
    /**
     * @var Pager
     */
    protected $_pager;

    /**
     * @var int
     */
    protected $_offset;

    /**
     * @var array
     */
    protected $_matchedModels = [];

    /**
     * @var Entity
     */
    protected $_entity;

    /**
     * @return array
     */
    abstract protected function getInitModels();

    /**
     * @return string
     */
    abstract protected function getPagerUrl();

    /**
     * @return void
     */
    abstract protected function doSearch();

    /**
     * @return string
     */
    abstract protected function getSearchContainerFactoryClass();

    /**
     * @return void
     */
    abstract protected function handleGetRequest();

    /**
     * @return void
     */
    abstract protected function setContext();

    /**
     * @return string
     */
    abstract protected function getPagerLimitConfig();

    protected function tryBody()
    {
        $this->setPagerByRequest();
        $this->handleRequest();
        $this->setContext();
    }

    protected function setPagerByRequest()
    {
        $limit 			= $this->getPagerLimitConfig();
        $currentPage 	= $this->request->param('page') ? $this->request->param('page') : 1;
        $this->_offset  = (($currentPage - 1) * $limit);

        $this->_pager = new Pager(
            $this->getInitModels(),
            $currentPage,
            $limit,
            $this->getPagerUrl()
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

        $this->doSearch();
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
        $industry   = new Model_Industry();
        $container  = $this->getSearchContainerFactoryClass();

        $this->context->container = $container::createContainer([
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
        $container = $this->getSearchContainerFactoryClass();
        $this->context->container = $container::createContainer([
            'search_term'   => Input::post('search_term'),
            'current'       => 'simple'
        ]);
    }
}