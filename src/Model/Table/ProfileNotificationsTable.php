<?php

namespace Notifier\Model\Table;

use Cake\Validation\Validator;

use Notifier\Model\Table\NotifierBaseTable;

class ProfileNotificationsTable extends NotifierBaseTable {
    protected $_serialized = ['content'];

    public function validationDefault(Validator $validator) {
        $validator
            ->requirePresence('entity_type', 'create')
            ->notEmptyString('entity_type');
        $validator
            ->requirePresence('entity_id', 'create')
            ->notEmptyString('entity_id');

        return $validator;
    }

    public function __construct (array $config = []) {
        parent::__construct($config);

        $this->hasMany('ProfileNotificationUsers', [
            'className' => 'Notifier.ProfileNotificationUsers'
        ]);
    }

    public function push (array $data = [], array $users = [], array $options = []) {
        $notification = $this->newEntity(['content' => $data] + $options);
        return $this->getConnection()->transactional(function () use ($notification, $users) {
            if ($this->save($notification)) {
                if (empty($users)) return false;

                $failure = collection($users)
                    ->map(function ($user_id) use ($notification) {
                        $notification_user = $this->NotificationUsers->newEntity([
                            'profile_notification_id'   => $notification->id,
                            'user_id'                   => $user_id
                        ]);
                        return $this->ProfileNotificationUsers->save($notification_user);
                    })
                    ->contains(false);
                return !$failure;
            }
            return false;
        });
    }
}