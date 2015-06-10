<?php
$xpdo_meta_map['Menu']= array (
  'package' => 'profiles',
  'version' => NULL,
  'table' => 'meteo_menus',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'menucache' => NULL,
    'generator' => '',
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
  ),
  'composites' => 
  array (
    'MenuEntry' => 
    array (
      'class' => 'MenuEntry',
      'local' => 'id',
      'foreign' => 'menuentry',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
