<?php
$xpdo_meta_map['MenuEntry']= array (
  'package' => 'profiles',
  'version' => NULL,
  'table' => 'meteo_menuentries',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'entry' => '',
    'name' => '',
    'type' => '',
    'menu' => 0,
  ),
  'fieldMeta' => 
  array (
    'entry' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
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
  'aggregates' => 
  array (
    'Menu' => 
    array (
      'class' => 'Menu',
      'local' => 'menu',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
