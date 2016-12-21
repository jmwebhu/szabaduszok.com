<?php

class Entity_Conversation extends Entity implements Conversation
{
    /**
     * @var Viewhelper_Conversation
     */
    protected $_viewhelper;

    /**
     * @var int
     */
    protected $_conversation_id;

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var string
     */
    protected $_slug;

    /**
     * @var string
     */
    protected $_created_at;

    /**
     * @var string
     */
    protected $_updated_at;

    /**
     * @return int
     */
    public function getConversationId()
    {
        return $this->_conversation_id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->_conversation_id;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->_model->getSlug();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_model->getName();
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->_model->getCreatedAt();
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->_model->getUpdatedAt();
    }

    /**
     * @return Conversation_Participant[]
     */
    public function getParticipants()
    {
        return $this->_model->getParticipants();
    }

    /**
     * @param int[]
     * @return Conversation_Participant[]
     */
    public function getParticipantsExcept(array $userIds)
    {
        return $this->_model->getParticipantsExcept($userIds);
    }

    public function __construct($value = null)
    {
        parent::__construct($value);
        $this->_viewhelper = new Viewhelper_Conversation($this, Auth::instance()->get_user());
    }

    /**
     * @return string
     */
    public function getParticipantNamesExcludeAuthUser($whichname = 'name')
    {
        $participants   = $this->getParticipants();
        $names          = '';
        $nameMethod     = 'get' . ucfirst($whichname);

        foreach ($participants as $i => $participant) {
            if ($participant->getId() != Auth::instance()->get_user()->user_id) {
                $tmp = $participant->{$nameMethod}();

                if (!Arr::isLastIndex($i, $participants, 1)) {
                    $tmp .= ', ';
                }

                $names .= $tmp;
            }
        }

        return $names;
    }

    /**
     * @return array of Message
     */
    public function getMessages()
    {
        return $this->_model->getMessages();
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    public function setParticipants(array $participant)
    {
        // TODO: Implement setParticipants() method.
    }

    /**
     * @param  int $conversationId
     * @param  array  $userIds
     * @return [type]
     */
    public static function getOrCreateWithUsersBy($conversationId, array $userIds)
    {
        $conversation = new Entity_Conversation($conversationId);
        if (!$conversation->loaded()) {
            $conversation = new Entity_Conversation();
            $conversation->submit(['users' => $userIds]);

            $conversations                          = Cache::instance()->get('conversations');
            $conversations[$conversation->getId()]  = $conversation->getModel();

            Cache::instance()->set('conversations', $conversations);

            
        }

        $conversation->getModel()->removeAll('conversation_interactions', 'conversation_id');

        return $conversation;
    }

    public function submit(array $data)
    {
        $transaction    = new Transaction_Conversation_Insert(new Model_Conversation(), $data);
        $this->_model   = $transaction->execute();
        $this->mapModelToThis();
    }

    /**
     * @param Conversation_Participant $user
     */
    public function deleteConversation(Conversation_Participant $user)
    {
        $interaction                    = new Model_Conversation_Interaction();
        $interaction->conversation_id   = $this->getId();
        $interaction->user_id           = $user->getId();
        $interaction->is_deleted        = true;
        $interaction->save();
    }

    /**
     * @param  array  $userIds
     * @return Entity_Conversation
     */
    public static function getConversationBetween(array $userIds)
    {
        $concatedUserIds    = Business_Conversation::getConcatedUserIdsFrom($userIds);
        $transaction        = Transaction_Conversation_Select_Factory::createSelect();
        $conversationId     = $transaction->getConversationIdBetween($concatedUserIds);

        return Entity_Conversation::getOrCreateWithUsersBy(
            $conversationId, $userIds);
    }

    /**
     * @param  string $whichname 'lastName'|'firstName'|'name'
     * @return string            
     */
    public function getParticipantNames($whichname = 'name')
    {
        return $this->_viewhelper->getParticipantNames($whichname);
    }

    /**
     * @return string[]
     */
    public function getParticipantProfilePictures()
    {
        return $this->_viewhelper->getParticipantProfilePictures();
    }

    /**
     * @param  int $userId
     * @return Entity_Conversation[]
     */
    public static function getForLeftPanelBy($userId)
    {
        $transaction    = Transaction_Conversation_Select_Factory::createSelect();
        $models         = $transaction->getForLeftPanelBy($userId);
        $entity         = new Entity_Conversation;

        return $entity->getEntitiesFromModels($models);
    }

    /**
     * @param  int $userId
     * @return Entity_Conversation[]
     */
    public function getMessagesBy($userId)
    {
        $transaction    = Transaction_Conversation_Select_Factory::createSelect($this->getModel());
        $models         = $transaction->getMessagesBy($userId);
        $entity         = new Entity_Message;

        return $entity->getEntitiesFromModels($models);   
    }

    /**
     * @param  array  $conversations
     * @param  int $userId
     * @return array
     */
    public static function getMessagesByConversationsAndUser(array $conversations, $userId)
    {
        $messages = [];
        foreach ($conversations as $conversation) {
            $messages[$conversation->getId()] = $conversation->getMessagesBy($userId);
        }

        return $messages;
    }
    

    /**
     * @param  int $userId
     * @return Entity_Message
     */
    public function getLastMessageBy($userId = null)
    {
        $userId     = ($userId) ? $userId : Auth::instance()->get_user()->user_id;
        $messages   = $this->getMessagesBy($userId);
        
        return Arr::last($messages);
    }

    /**
     * @param  int  $userId
     * @return boolean
     */
    public function hasUnreadMessageBy($userId = null)
    {
        $userId         = ($userId) ? $userId : Auth::instance()->get_user()->user_id;
        $transaction    = new Transaction_Conversation_Count($this->_model, $userId);
        $counts         = $transaction->execute();

        return ($counts['unread'] != 0);
    }

    /**
     * @return boolean
     */
    public function flagMessagesAsRead()
    {
        return (new Transaction_Conversation_Update($this->getModel()))
            ->flagMessagesAsRead(Auth::instance()->get_user()->user_id);
    }
    
}