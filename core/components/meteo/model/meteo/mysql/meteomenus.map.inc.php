<?php
$xpdo_meta_map['MeteoMenus']= array (
  'package' => 'meteo',
  'version' => '1.1',
  'table' => 'meteo_menus',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'menucache' => NULL,
    'generator' => '',
    'name' => '',
  ),
  'fieldMeta' => 
  array (
    'menucache' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'generator' => 
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
  ),
);
