<?php
$xpdo_meta_map['MigxFormtabs']= array (
  'package' => 'meteo',
  'version' => '1.1',
  'table' => 'migx_formtabs',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'config_id' => 0,
    'caption' => '',
    'pos' => 0,
    'print_before_tabs' => 0,
    'extended' => NULL,
  ),
  'fieldMeta' => 
  array (
    'config_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'caption' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'pos' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'print_before_tabs' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'extended' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'config_id' => 
    array (
      'alias' => 'config_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'config_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
