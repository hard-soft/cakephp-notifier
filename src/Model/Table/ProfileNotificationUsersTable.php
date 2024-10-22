<?php
namespace Notifier\Model\Table;

use Cake\Validation\Validator;
use Notifier\Model\Table\NotifierBaseTable;

class ProfileNotificationUsersTable extends NotifierBaseTable {
	public function __construct(array $config = []) {
		parent::__construct($config);

		$this->belongsTo('ProfileNotifications', [
			'foreignKey' => 'profile_notification_id',
		]);
	}

	public function validationDefault(Validator $validator): Validator {
		$validator
			->requirePresence('profile_notification_id', 'create')
			->notEmptyString('profile_notification_id');
		$validator
			->requirePresence('user_id', 'create')
			->notEmptyString('user_id');

		return $validator;
	}

	public function setStatus($id = null, $status = null) {
		if (!empty($id) && $status !== null) {
			$entity         = $this->get($id);
			$entity->status = $status;
			if ($this->save($entity)) {
				return true;
			}
		}

		return false;
	}

	public function purge($id = null) {
		if (!empty($id)) {
			$entity                  = $this->get($id);
			$profile_notification_id = $entity->profile_notification_id;
			if ($this->delete($entity)) {
				$notification = $this->ProfileNotifications->get($profile_notification_id, ['contain' => ['ProfileNotificationUsers', 'ProfileNotificationEntities']]);
				if (count($notification->profile_notification_users) == 0) {
					$this->ProfileNotifications->delete($notification);
				}

				return true;
			}
		}

		return false;
	}
}