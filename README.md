# Cakephp Notifier-Plugin
## Description
This plugin is built on Cakephp 2.x for saving notification messages with polymorphic associations to entities of your application.
It is only the legacy version of the plugin, therefore not all features are available that are available in the most recent version.

### PRO-tips:
To prefix your tables with a certain key set the Configure like:

```php
    Configure::write('Notifier.table_prefix', "mytableprefix_");
```

### Usage
```php
    $notificationTable = ClassRegistry::init('Notifier.ProfileNotification');
    $data = [
        //variables that should be saved as serialized data
    ];
    $options     = [
        'createdby' => '[CREATEDBY]',
        'type'      => INT_TYPE,               //task done,
    ];
    $users    = ['[USER_1_ID]', '[USER_2_ID]'];
    $entities = [
        [
            'entity_type' => '[ENTITY_ALIAS]',
            'entity_id'   => '[ENITTY_ID]',
        ]
    ];
    $notificationTable->push($data, $options, $users, $entities);
```