<?php
$xpdo_meta_map['MeteoUsers']= array (
  'package' => 'meteo',
  'version' => '1.1',
  'table' => 'meteo_users',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'shvnr' => '',
    'name' => '',
  ),
  'fieldMeta' => 
  array (
    'shvnr' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
  ),
  'indexes' => 
  array (
    'shvnr' => 
    array (
      'alias' => 'shvnr',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'shvnr' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
