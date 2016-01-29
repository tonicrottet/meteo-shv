<?php
$xpdo_meta_map['MeteoMenuentries']= array (
  'package' => 'meteo',
  'version' => '1.1',
  'table' => 'meteo_menuentries',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'entry' => NULL,
    'name' => '',
    'type' => '',
    'menu' => 0,
  ),
  'fieldMeta' => 
  array (
    'entry' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'menu' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
);
