<?php
$xpdo_meta_map['SHVMeteoUser']= array (
  'package' => 'meteo',
  'version' => NULL,
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
);
