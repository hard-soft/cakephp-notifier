<?php

namespace Notifier\Model\Table;

use Cake\Validation\Validator;
use Cake\Datasource\Exception\RecordNotFoundException;

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

    public function setStatus ($id = null, $status = null) {
        if (!empty($id) && $status !== null) {
            $entity = $this->get($id);
            $entity->status = $status;
            if ($this->save($entity)) {
                return true;
            }
        }
        return false;
    }

    public function purge ($id = null) {
        if (!empty($id)) {
            $entity = $this->get($id);
            if ($this->delete($entity)) {
                return true;
            }
        }
        return false;
    }
}