<?php

/**
 * class Model_Project
 *
 * Felelosseg: Projekt lekerdezesek
 */
class Model_Project extends ORM
{
    protected $_table_name = 'projects';
    protected $_primary_key = 'project_id';

    protected $_created_column = [
        'column' => 'created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_updated_column = [
        'column' => 'updated_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_table_columns = [
        'project_id' => ['type' => 'int', 'key' => 'PRI'],
        'user_id' => ['type' => 'int', 'null' => true],
        'name' => ['type' => 'string', 'null' => true],
        'short_description' => ['type' => 'string', 'null' => true],
        'long_description' => ['type' => 'string', 'null' => true],
        'email' => ['type' => 'string', 'null' => true],
        'phonenumber' => ['type' => 'string', 'null' => true],
        'is_active' => ['type' => 'tinyint', 'null' => true],
        'is_paid' => ['type' => 'tinyint', 'null' => true],
        'search_text' => ['type' => 'string', 'null' => true],
        'expiration_date' => ['type' => 'datetime', 'null' => true],
        'salary_type' => ['type' => 'int', 'null' => true],
        'salary_low' => ['type' => 'int', 'null' => true],
        'salary_high' => ['type' => 'int', 'null' => true],
        'slug' => ['type' => 'string', 'null' => true],
        'created_at' => ['type' => 'datetime', 'null' => true],
        'updated_at' => ['type' => 'datetime', 'null' => true]
    ];

    protected $_belongs_to = [
        'user' => [
            'model' => 'User',
            'foreign_key' => 'user_id'
        ]
    ];

    protected $_has_many = [
        'industries' => [
            'model' => 'Industry',
            'through' => 'projects_industries',
            'far_key' => 'industry_id',
            'foreign_key' => 'project_id',
        ],
        'professions' => [
            'model' => 'Profession',
            'through' => 'projects_professions',
            'far_key' => 'profession_id',
            'foreign_key' => 'project_id',
        ],
        'skills' => [
            'model' => 'Skill',
            'through' => 'projects_skills',
            'far_key' => 'skill_id',
            'foreign_key' => 'project_id',
        ],
        'partners' => [
            'model' => 'Project_Partner',
            'foreign_key' => 'project_id',
        ],
    ];

    public function rules()
    {
        return [
            'user_id' => [['not_empty']],
            'name' => [['not_empty']],
            'short_description' => [['not_empty']],
            'long_description' => [['not_empty']],
            'email' => [['not_empty'], ['email'], ['email_domain']],
            'phonenumber' => [['not_empty']],
            'salary_type' => [['not_empty']],
            'salary_low' => [['not_empty'], ['numeric']],
            'salary_high' => [['numeric']]
        ];
    }

    /**
     * @return Array_Builder
     */
    public function baseSelect()
    {
        $base = parent::baseSelect();
        return $base->where('is_active', '=', 1);
    }

    /**
     * @param array $post
     * @return ORM
     */
    public function submit(array $post)
    {
        $id = Arr::get($post, 'project_id');

        if (!$id) {
            unset($post['project_id']);
            $post['expiration_date'] = date('Y-m-d', strtotime('+1 month'));
        }

        $post = $this->setDefaultProperties($post);
        parent::submit($post);

        $this->saveSlug();
        $this->addRelations($post);

        if (!$id) {
            Notification_User::notifyProjectNew($this);
        }

        return $this;
    }

    /**
     * @return array ['error']
     */
    public function inactivate()
    {
        try {
            $error = false;
            $message = '';
            Model_Database::trans_start();
            $this->throwExceptionIfCannotDelete();

            $this->is_active = 0;
            $this->save();

        } catch (Exception $ex) {
            $error = true;
            $message = 'Sajnos valami hiba történt...';
            Log::instance()->addException($ex);

        } catch (HTTP_Exception_403 $ex) {
            $error = true;
            $message = $ex->getMessage();

        } finally {
            Model_Database::trans_end([!$error]);

            if (!$error) {
                $this->clearCache();
            }
        }

        return ['error' => $error, 'message' => $message];
    }

    /**
     * @throws HTTP_Exception_403
     */
    protected function throwExceptionIfCannotDelete()
    {
        $authorization = new Authorization_Project($this);

        if (!$authorization->canDelete()) {
            throw new HTTP_Exception_403('Nincs jogosultságod törölni a projektet');
        }
    }

    /**
     * @param array $post
     * @return array
     */
    protected function setDefaultProperties(array $post)
    {
        $data = $post;

        $data['user_id'] = Auth::instance()->get_user()->user_id;
        $data['is_paid'] = 1;
        $data['is_active'] = 1;

        return $data;
    }

    /**
     * @param array $post ['industries', 'professions', 'skills']
     */
    protected function addRelations(array $post)
    {
        $this->removeAll('projects_industries', 'project_id');
        $this->removeAll('projects_professions', 'project_id');
        $this->removeAll('projects_skills', 'project_id');

        $this->addRelation($post, new Model_Project_Industry(), new Model_Industry());
        $this->addRelation($post, new Model_Project_Profession(), new Model_Profession());
        $this->addRelation($post, new Model_Project_Skill(), new Model_Skill());
    }

    /**
     * Visszaadja, hogy hany aktiv projekt van
     *
     * @return int
     */
    public function getCount()
    {
        return $this->baseSelect()->execute()->count();
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getOrderedAndLimited($limit, $offset)
    {
        return $this->orderBy('created_at')
            ->limit($limit)->offset($offset)
            ->execute()->as_array();
    }

    /**
     * @param bool $execute
     * @param string $direction
     * @return Array_Builder|array
     */
    public function getOrderedByCreated($execute = true, $direction = 'DESC')
    {
        $builder = $this->orderBy('created_at', $direction);

        if ($execute) {
            return $builder->execute()->as_array();
        }

        return $builder;
    }

    /**
     * @param string $field
     * @param string $direction
     * @return Array_Builder
     */
    protected function orderBy($field, $direction = 'DESC')
    {
        return $this->baseSelect()->order_by($field, $direction);
    }

    /**
     * @return array ['industries', 'professions', 'skills']
     */
    public function getRelations()
    {
        if (!$this->loaded()) {
            return [];
        }

        return [
            'industries' => $this->getRelation(new Model_Project_Industry()),
            'professions' => $this->getRelation(new Model_Project_Profession()),
            'skills' => $this->getRelation(new Model_Project_Skill())
        ];
    }

    /**
     * @return array
     */
    public function getPartners()
    {
        return [
            'candidates' => $this->partners->where('type', '=', Model_Project_Partner::TYPE_CANDIDATE)->find_all(),
            'participants' => $this->partners->where('type', '=', Model_Project_Partner::TYPE_PARTICIPANT)->find_all()
        ];
    }

    /**
     * @param ORM $relationModel
     * @return array
     */
    protected function getRelation(ORM $relationModel)
    {
        $relations = $relationModel->getAll();
        $projectRelations = Arr::get($relations, $this->project_id, []);

        return $projectRelations;
    }
}
