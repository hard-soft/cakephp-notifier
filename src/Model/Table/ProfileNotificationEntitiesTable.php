<?php

namespace Notifier\Model\Table;

use Cake\Validation\Validator;

use Notifier\Model\Table\NotifierBaseTable;

class ProfileNotificationEntitiesTable extends NotifierBaseTable {
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
            ->requirePresence('entity_type', 'create')
            ->notEmptyString('entity_type');
        $validator
            ->requirePresence('entity_id', 'create')
            ->notEmptyString('entity_id');

        return $validator;
    }
}

?>