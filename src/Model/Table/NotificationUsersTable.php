<?php

namespace Notifier\Model\Table;

use Cake\Validation\Validator;

use Notifier\Model\Table\NotifierBaseTable;

class NotificationUsersTable extends NotifierBaseTable {
    public function __construct (array $config = []) {
        parent::__construct($config);

        $this->belongsTo('Notifications', [
            'className' => 'Notifier.Notifications'
        ]);
    }

    public function validationDefault(Validator $validator) {
        $validator
            ->requirePresence('notification_id', 'create')
            ->notEmptyString('notification_id');
        $validator
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        return $validator;
    }
}