<?php
$xpdo_meta_map['ShvmeteoUsers']= array (
  'package' => 'meteo',
  'version' => '1.1',
  'table' => 'shvmeteo_users',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'SHVUser' => NULL,
    'MeteoUser' => NULL,
    'data' => '',
  ),
  'fieldMeta' => 
  array (
    'SHVUser' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'unique',
    ),
    'MeteoUser' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'data' => 
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
    'shvuser' => 
    array (
      'alias' => 'shvuser',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'SHVUser' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
);
