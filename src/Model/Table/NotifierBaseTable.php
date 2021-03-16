<?php
namespace Notifier\Model\Table;

use Cake\Core\Configure;
use Cake\Database\Schema\TableSchema;
use Cake\Database\Type;

use App\Model\Table\AppTable;
use Notifier\Database\Type\SerializeType;

class NotifierBaseTable extends AppTable {
    protected $_serialized = [];

    /**
     * {@inheritdoc}
     */
    public function initialize(array $config = []) {
        Type::map('notifier.serialize', SerializeType::class);

        if (Configure::check('Notifier.table_prefix')) {
            $this->setTable(Configure::read('Notifier.table_prefix') . $this->getTable());
        }
    }
    /**
     * Sets the column type for template_vars and headers to json.
     *
     * @param TableSchema $schema The table description
     * @return TableSchemas
     */
    protected function _initializeSchema(TableSchema $schema) {
        if (!empty($this->_serialized)) {
            foreach ($this->_serialized as $col) {
                $schema->setColumnType($col, 'notifier.serialize');
            }
        }
        return $schema;
    }
}