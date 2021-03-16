<?php

namespace Notifier\Model\Table;

use Cake\Validation\Validator;

use Notifier\Model\Table\NotifierBaseTable;

class ProfileNotificationUsersTable extends NotifierBaseTable {
    public function __construct (array $config = []) {
        parent::__construct($config);

        $this->belongsTo('ProfileNotifications', [
            'className' => 'Notifier.ProfileNotifications'
        ]);
    }

    public function validationDefault(Validator $validator) {
        $validator
            ->requirePresence('profile_notification_id', 'create')
            ->notEmptyString('profile_notification_id');
        $validator
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        return $validator;
    }
}