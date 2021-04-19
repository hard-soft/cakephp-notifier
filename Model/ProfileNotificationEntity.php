<?php

App::uses('NotifierBaseModel', 'Notifier.Model');

class ProfileNotificationEntity extends NotifierBaseModel {
    public $belongsTo = [
        'ProfileNotification' => [
            'foreignKey' => 'profile_notification_id'
        ]
    ];
}

?>