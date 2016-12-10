<?php

class Transaction_Conversation_Insert implements Transaction
{
    /**
     * @var Model_Conversation
     */
    protected $_conversation = null;

    /**
     * @var array
     */
    protected $_data = [];

    /**
     * @param Model_Conversation $conversation
     * @param array $data
     */
    public function __construct(Model_Conversation $conversation, array $data)
    {
        $this->_conversation    = $conversation;
        $this->_data            = $data;
    }

    /**
     * @return Model_Conversation
     */
    public function getConversation()
    {
        return $this->_conversation;
    }

    /**
     * @return Model_Conversation
     */
    public function execute()
    {
        $entities = [];
        foreach ($this->_data['users'] as $id) {
            $model      = new Model_User($id);
            $entities[] = Entity_User::createUser($model->type, $model);
        }

        $this->_data['name'] = Text_Conversation::getNameFromUsers($entities);
        $this->_conversation->submit($this->_data);

        $this->_conversation->saveSlug();
        $this->addRelations($this->_data);

        return $this->_conversation;
    }

    /**
     * @param array $data
     */
    protected function addRelations(array $data)
    {
        $this->_conversation->removeAll('conversations_users', 'conversation_id');

        /*var_dump('addRelations');
        var_dump($data);*/

        $this->_conversation->addRelation($data, new Model_Conversation_User(), new Model_User());
    }
}