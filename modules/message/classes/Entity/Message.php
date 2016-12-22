<?php

class Entity_Message extends Entity implements Message, Notification_Subject
{
    /**
     * @var Viewhelper_Message
     */
    protected $_viewhelper;
    

    /**
     * @var int
     */
    protected $_message_id;

    /**
     * @var int
     */
    protected $_conversation_id;

    /**
     * @var int
     */
    protected $_sender_id;

    /**
     * @var string
     */
    protected $_message;

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
    public function getMessageId()
    {
        return $this->_message_id;
    }

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
    public function getSenderId()
    {
        return $this->_sender_id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_model->getId();
    }

    /**
     * @return Conversation_Participant
     */
    public function getSender()
    {
        return $this->_model->getSender();
    }

    /**
     * @return Conversation
     */
    public function getConversation()
    {
        return $this->_model->getConversation();
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_model->getMessage();
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

    public function send(array $data)
    {
        return $this->submit($data);
    }

    public function __construct($value = null)
    {
        parent::__construct($value);
        $this->_viewhelper = Viewhelper_Message_Factory::createViewhelper($this, Auth::instance()->get_user()->user_id);
    }
    

    /**
     * @param Conversation_Participant $user
     */
    public function deleteMessage(Conversation_Participant $user)
    {
        $delete = Transaction_Message_Delete_Factory::createDelete($this, $user);
        $delete->execute();
    }

    /**
     * @param array $data
     */
    public function submit(array $data, Gateway_Socket_Message $socket = null)
    {
        $transaction    = new Transaction_Message_Insert($this->_model, $data);
        $this->_model   = $transaction->execute();
        $this->mapModelToThis();

        $conversation   = new Entity_Conversation($this->_model->conversation);
        $message        = new Entity_Message($this->_model);

        if (!$socket) {
            $socket         = new Gateway_Socket_Message;
        }
    
        $socket->signalNew($conversation, $message);

        $this->sendNotification(new Transaction_Message_Select($this->_model));

        return $this->_message_id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_viewhelper->getType();
    }

    /**
     * @return string
     */
    public function getColor($userId = null)
    {
        return $this->_viewhelper->getColor();
    }

    /**
     * @return string
     */
    public function getCreatedatForView()
    {
        return $this->_viewhelper->getCreatedAt();
    }

    public function sendNotification(Transaction_Message_Select $selectTransaction)
    {
        $senderModel    = $this->getSender()->getModel();  
        $users          = $this->_model->conversation->users
            ->where('conversations_users.user_id', '!=', $senderModel->user_id)->find_all();

        foreach ($users as $user) {
            $this->sendNotificationToOneUserIfRequired($selectTransaction, $user);
        }
    }

    protected function sendNotificationToOneUserIfRequired(Transaction_Message_Select $selectTransaction, Model_User $user)
    {
        if ($selectTransaction->shouldSendNotificationTo($user->user_id)) {                
            $senderModel        = $this->getSender()->getModel();  
            $extraData          = ['message' => $this->getMessage()];

            $notifierEntity     = Entity_User::createUser($senderModel->type, $senderModel);
            $notifiedEntity     = Entity_User::createUser($user->type, $user);
            $notification       = Entity_Notification::createFor(Model_Event::TYPE_MESSAGE_NEW, $this, $notifierEntity, $notifiedEntity, $extraData);    

            $notifiedEntity->setNotification($notification);
            $notifiedEntity->sendNotification();
        }
    }

    /**
     * @return string
     */
    public function getSubjectType()
    {
        return 'message';
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_model->object();
    }

    /**
     * @return string
     */
    public function getNotificationUrl()
    {
        return Route::url('messagesList', ['slug' => $this->getModel()->conversation->slug]);
    }
}