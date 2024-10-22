<?php

namespace Notifier\Model\Table;

use Notifier\Model\Table\NotifierBaseTable;

class ProfileNotificationsTable extends NotifierBaseTable {
    protected $_serialized = ['content'];
    public $_entity_types  = [];

    public function __construct (array $config = []) {
        parent::__construct($config);

        $this->hasMany('ProfileNotificationUsers', [
            'className'     => 'Notifier.ProfileNotificationUsers',
            'foreignKey'    => 'profile_notification_id',
            'dependent'     => true,
        ]);
        $this->hasMany('ProfileNotificationEntities', [
            'className'     => 'Notifier.ProfileNotificationEntities',
            'foreignKey'    => 'profile_notification_id',
            'dependent'     => true,
        ]);
    }

    public function initialize(array $config = []):void {
        parent::initialize($config);

        if (!empty($this->_entity_types)) {
            foreach ($this->_entity_types as $type) {
                $this->belongsToMany($type, [
                    'through'           => 'Notifier.ProfileNotificationEntities',
                    'foreignKey'        => 'profile_notification_id',
                    'targetForeignKey'  => 'entity_id',
                    'conditions'        => ['entity_type' => $type],
                    'dependent'         => true,
                ]);
            }
        }
    }

    public function push (array $data = [], array $options = [], array $users = [], array $entities = []) {
        $notification = $this->newEntity(['content' => $data] + $options);
        return $this->getConnection()->transactional(function () use ($notification, $users, $entities) {
            if ($this->save($notification)) {
                if (empty($users)) return false;

                $failure_entities = false;
                if (!empty($entities)) {
                    $failure_entities = collection($entities)
                        ->map(function ($entity) use ($notification) {
                            $notification_entity = $this->ProfileNotificationEntities->newEntity([
                                'profile_notification_id'   => $notification->id
                            ] + $entity);
                            return $this->ProfileNotificationEntities->save($notification_entity);
                        })
                        ->contains(false);
                }

                $failure_users = collection($users)
                    ->map(function ($user_id) use ($notification) {
                        $notification_user = $this->ProfileNotificationUsers->newEntity([
                            'profile_notification_id'   => $notification->id,
                            'user_id'                   => $user_id
                        ]);
                        return $this->ProfileNotificationUsers->save($notification_user);
                    })
                    ->contains(false);

                return !$failure_users && !$failure_entities;
            }
            return false;
        });
    }

    public function getEntityTypes () {
        return $this->_entity_types;
    }
}
