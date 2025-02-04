<?php
namespace Notifier\Model\Table;

use Cake\Database\Type;
use Cake\Core\Configure;
use App\Model\Table\AppTable;
use Cake\Database\Schema\TableSchema;
use Notifier\Database\Type\SerializeType;
use Cake\Database\Schema\TableSchemaInterface;

class NotifierBaseTable extends AppTable {
	protected $_serialized = [];

	/**
	 * {@inheritdoc}
	 */
	public function initialize(array $config = []): void {
		parent::initialize($config);

		Type::map('notifier.serialize', SerializeType::class);

		if (Configure::check('Notifier.table_prefix')) {
			$this->setTable(Configure::read('Notifier.table_prefix') . $this->getTable());
		}

		$schema = $this->getSchema();
		if (!empty($this->_serialized)) {
			foreach ($this->_serialized as $col) {
				$schema->setColumnType($col, 'notifier.serialize');
			}
		}
		$this->setSchema($schema);
	}
}
