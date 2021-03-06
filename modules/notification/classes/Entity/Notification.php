<?php

class Entity_Notification extends Entity implements Notification
{
    /**
     * @var int
     */
    protected $_notification_id;

    /**
     * @var int
     */
    protected $_notifier_user_id;

    /**
     * @var int
     */
    protected $_notified_user_id;

    /**
     * @var int
     */
    protected $_subject_id;

    /**
     * @var string
     */
    protected $_subject_name;

    /**
     * @var string
     */
    protected $_event_id;

    /**
     * @var string
     */
    protected $_url;

    /**
     * @var int
     */
    protected $_is_archived;

    /**
     * @var string
     */
    protected $_extra_data_json;

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
    public function getNotificationId()
    {
        return $this->_notification_id;
    }

    /**
     * @return int
     */
    public function getNotifierUserId()
    {
        return $this->_notifier_user_id;
    }

    /**
     * @param int $notifier_user_id
     */
    public function setNotifierUserId($notifier_user_id)
    {
        $this->_notifier_user_id = $notifier_user_id;
    }

    /**
     * @return int
     */
    public function getNotifiedUserId()
    {
        return $this->_notified_user_id;
    }

    /**
     * @param int $notified_user_id
     */
    public function setNotifiedUserId($notified_user_id)
    {
        $this->_notified_user_id = $notified_user_id;
    }

    /**
     * @return int
     */
    public function getSubjectId()
    {
        return $this->_subject_id;
    }

    /**
     * @param int $subject_id
     */
    public function setSubjectId($subject_id)
    {
        $this->_subject_id = $subject_id;
    }

    /**
     * @return string
     */
    public function getSubjectName()
    {
        return $this->_subject_name;
    }

    /**
     * @param string $subject_name
     */
    public function setSubjectName($subject_name)
    {
        $this->_subject_name = $subject_name;
    }

    /**
     * @return string
     */
    public function getEventId()
    {
        return $this->_event_id;
    }

    /**
     * @param string $event_id
     */
    public function setEventId($event_id)
    {
        $this->_event_id = $event_id;
    }

    /**
     * @return int
     */
    public function getIsArchived()
    {
        return $this->_is_archived;
    }

    /**
     * @param int $is_archived
     */
    public function setIsArchived($is_archived)
    {
        $this->_is_archived = $is_archived;
    }

    /**
     * @return string
     */
    public function getExtraDataJson()
    {
        return $this->_extra_data_json;
    }

    /**
     * @param string $extra_data_json
     */
    public function setExtraDataJson($extra_data_json)
    {
        $this->_extra_data_json = $extra_data_json;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->_created_at;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->_created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->_updated_at;
    }

    /**
     * @param string $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->_updated_at = $updated_at;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_model->object();
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return Model_Event_Factory::createEvent($this->_model->event_id);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_model->url;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_notification_id;
    }

    /**
     * @return Notification_Subject
     */
    public function getSubject()
    {
        $class = strtoupper($this->getSubjectName());
        $entityClass = 'Entity_' . $class;

        if (class_exists($entityClass)) {
            return new $entityClass($this->getSubjectId());
        }

        $modelClass = 'Model_' . $class;

        return new $modelClass($this->getSubjectId());
    }

    /**
     * @return Notifiable
     */
    public function getNotifier()
    {
        $class = $this->getEvent()->getNotifierClass();
        return new $class($this->getNotifierUserId());
    }

    /**
     * @return Notifiable
     */
    public function getNotified()
    {
        $class = $this->getEvent()->getNotifiedClass();
        return new $class($this->getNotifiedUserId());
    }

    public function archive()
    {
        $this->_model->is_archived = true;
        $this->_model->save();
    }

    /**
     * @return bool
     */
    public function isArchived()
    {
        return (bool)$this->_model->is_archived;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getFromExtraData($key)
    {
        return $this->_model->getFromExtraData($key);
    }

    /**
     * @param int $eventType
     * @param Notification_Subject $subject
     * @param Notifiable $notifier
     * @param Notifiable $notified
     * @param array|null $extraData
     * @return Entity_Notification
     */
    public static function createFor($eventType, Notification_Subject $subject, Notifiable $notifier, Notifiable $notified, $extraData = null)
    {
        $extraData      = ($extraData == null) ? [] : $extraData;
        $event          = Model_Event_Factory::createEvent($eventType);
        $notifierId     = null;
        $notifiedId     = null;

        $notifierId = $notifier->getId();
        $notifiedId = $notified->getId();

        Assert::notNull($notifier);
        Assert::notNull($notified);

        $notification = new Entity_Notification();
        $notification->setNotifierUserId($notifierId);
        $notification->setNotifiedUserId($notifiedId);
        $notification->setEventId($event->getId());
        $notification->setSubjectId($subject->getId());
        $notification->setSubjectName($subject->getSubjectType());
        $notification->setUrl($subject->getNotificationUrl());

        if ($extraData) {
            $notification->setExtraDataJson(json_encode($extraData));
        }

        $notification->save();

        return $notification;
    }
}