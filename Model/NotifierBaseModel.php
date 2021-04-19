<?php
App::uses('AppModel', 'Model');
App::uses('SerializeType', 'Notifier.Model/Types');

class NotifierBaseModel extends AppModel {
    use SerializeType;
    
    public $actsAs = ['Containable'];
    protected $_serialized = [];

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);

        if (Configure::check('Notifier.table_prefix')) {
            $this->tablePrefix = Configure::read('Notifier.table_prefix');
        }
    }

    /**
     * Converts array data for template vars into a json serialized string
     *
     * @param array $options
     * @return boolean
     **/
	public function beforeSave ($options = array()) {
		foreach ($this->_serialized as $f) {
			if (isset($this->data[$this->alias][$f])) {
				$this->data[$this->alias][$f] = $this->toDatabase($this->data[$this->alias][$f]);
			}
		}
		return parent::beforeSave($options);
	}

    /**
     * Converts template_vars back into a php array
     *
     * @param array $results
     * @param boolean $primary
     * @return array
     **/
	public function afterFind ($results, $primary = false) {
		if (!$primary) {
			return parent::afterFind($results, $primary);
		}

		foreach ($results as $key => &$r) {
			foreach ($this->_serialized as $f) {
				if (isset($results[$key][$this->alias][$f])) {
					$results[$key][$this->alias][$f] = $this->toPHP($results[$key][$this->alias][$f]);
				}
			}
		}

		return $results;
	}
}