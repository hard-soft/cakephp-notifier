<?php

namespace Notifier\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\I18n\FrozenTime;

class NotifierComponent extends Component {
    public $_notification = null;

    public function create (array $data = [], array $users = [], array $options = []) {
        $this->_notification = [
            'data'      => $data,
            'users'     => $users,
            'options'   => $options
        ];
        return $this;
    }

    public function setEntity (Entity $entity) {
        $this->_notification['options'] = array_merge($this->_notification['options'], [
            'entity_id' => $entity->id,
            'entity_type' => $entity->source()
        ]);
        return $this;
    }

    public function setOptions (array $options = []) {
        $this->_notification['options'] = array_merge($this->_notification['options'], $options);
        return $this;
    }

    public function getOptions () {
        return $this->_notification['options'];
    }

    public function setData (array $data = []) {
        $this->_notification['data'] = array_merge($this->_notification['data'], $data);
        return $this;
    }

    public function getData () {
        return $this->_notification['data'];
    }

    public function setUsers (array $users = []) {
        $this->_notification['users'] = array_merge($this->_notification['users'], $users);
        return $this;
    }

    public function getUsers () {
        return $this->_notification['users'];
    }

    public function setDueDate (FrozenTime $datetime) {
        return $this->setOptions(['due' => $datetime]);
    }

    public function getDueDate () {
        return $this->_notification['options']['due'] ?? null;
    }

    public function push (array $data = [], array $users = [], array $options = []) {
        if (!empty($data) || !empty($users) || !empty($options)) {
            $this->create($data, $users, $options);
        }

        $notificationsTable = TableRegistry::getTableLocator()->get('Notifier.Notifications');
        return $notificationsTable->push($this->_notification['data'], $this->_notification['users'], $this->_notification['options']);
    }
}