<?php

App::uses('NotifierBaseModel', 'Notifier.Model');
App::uses('Inflector', 'Utility');

class ProfileNotification extends NotifierBaseModel {
    protected $_serialized = ['content'];
    public $_entity_types  = [];

    public $hasMany = [
        'ProfileNotificationUser' => [
            'className'     => 'Notifier.ProfileNotificationUser',
            'foreignKey'    => 'profile_notification_id',
            'dependent'     => true,
        ],
        'ProfileNotificationEntity' => [
            'className'     => 'Notifier.ProfileNotificationEntity',
            'foreignKey'    => 'profile_notification_id',
            'dependent'     => true,
        ]
    ];


    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);

        if (!empty($this->_entity_types)) {
            foreach ($this->_entity_types as $type) {
                $this->hasAndBelongsToMany[$type] = [
                    'className'         => Inflector::singularize($type),
                    'with'              => 'Notifier.ProfileNotificationEntity',
                    'foreignKey'        => 'profile_notification_id',
                    'associationForeignKey'  => 'entity_id',
                    'conditions'        => ['entity_type' => $type],
                    'dependent'         => true,
                ];
            }
        }
    }
    
    public function push (array $data = [], array $options = [], array $users = [], array $entities = []) {
        $notification = ['content' => $data] + $options;
        $transactions = [];
        $transactions[] = $this->getDataSource();
        $transactions[] = $this->ProfileNotificationUser->getDataSource();
        $transactions[] = $this->ProfileNotificationEntity->getDataSource();
        try {
            if ($this->save($notification)) {
                if (empty($users)) return false;

                if (!empty($entities)) {
                    foreach ($entities as $entity) {
                        $new = ['profile_notification_id'   => $this->id] + $entity;
                        $this->ProfileNotificationEntity->create();
                        if (!$this->ProfileNotificationEntity->save($new)) {
                            throw new Exception('failed to save notification entity');
                        }
                    }
                }
                
                foreach ($users as $user_id) {
                    $new = [
                        'profile_notification_id'   => $this->id,
                        'user_id'                   => $user_id
                    ];
                    $this->ProfileNotificationUser->create();
                    if (!$this->ProfileNotificationUser->save($new)) {
                        throw new Exception('failed to save notification user');
                    }
                }

                foreach ($transactions as $t) {
                    $t->commit();
                }
                return true;
            } else {
                throw new Exception('failed to save notification');
            }
        } catch (Exception $e) {
            foreach ($transactions as $t) {
                $t->rollback();
            }
        }
        return false;
    }
}