<?php

App::uses('NotifierBaseModel', 'Notifier.Model');

class ProfileNotificationUser extends NotifierBaseModel {
    public $belongsTo = [
        'ProfileNotifications' => [
            'foreignKey' => 'profile_notification_id'
        ]
    ];

    public function setStatus ($id = null, $status = null) {
        if (!empty($id) && $status !== null) {
            $this->contain([]);
            $entity = $this->read(null, $id);
            $entity[$this->alias]['status'] = $status;
            if ($this->save($entity)) {
                return true;
            }
        }
        return false;
    }

    public function purge ($id = null) {
        if (!empty($id)) {
            $this->contain([]);
            $entity = $this->read(null, $id);
            $profile_notification_id = $entity[$this->alias]['profile_notification_id'];
            if ($this->delete($id)) {
                $this->ProfileNotification->contain(['ProfileNotificationUser']);
                $notification = $this->ProfileNotification->read(null, $profile_notification_id);
                if (count($notification['ProfileNotificationUser']) == 0) {
                    $this->ProfileNotification->delete($profile_notification_id);
                }
                return true;
            }
        }
        return false;
    }
}