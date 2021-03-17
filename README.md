# Cakephp Notifier-Plugin
## Description
This plugin is built on Cakephp 3.9 for saving notification messages with polymorphic associations to entities of your application.

### PRO-tips:
To prefix your tables with a certain key set the Configure like:

```php
    use Cake\Core\Configure;

    Configure::write('Notifier.table_prefix', "mytableprefix_");
```

### Usage:
To use load the Component:
```php
    $this->Notifier = $this->loadComponent('Nofifier.Notifier');
```

Basic example:
- 2 users assoicated
- no type
- basic message var

```php
    $this->Notifier = $this->loadComponent('Nofifier.Notifier');
    $this->Notifier->create([
        'message' => 'this is a testmessage'
    ])
    ->setUsers(['user1_id', 'user2_id'])
    ->push();
```


Advanced example:
- 2 users assoicated
- certain type
- multiple associated entities (either by array or entity)
- complex var types (possible by serialization)

```php
    $this->Notifier = $this->loadComponent('Nofifier.Notifier');
    $this->Notifier->create([
        'start' => new FrozenTime()
    ])
    ->setUsers(['user1_id', 'user2_id'])
    ->addEntity($entity)
    ->addEntityItem([
        'entity_type' => '[OTHER_ENTITY_TABLE_ALIAS]',
        'entity_id'   => '[OTHER_ENTITY_ID]'
    ])
    ->setType(2)    //integer type, used to switch templates in main application
    ->push();
```
