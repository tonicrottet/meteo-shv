<?php
$xpdo_meta_map['ViewEntry']= array (
  'package' => 'profiles',
  'version' => NULL,
  'table' => 'meteo_viewentries',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'entrydata' => NULL,
    'entryname' => '',
    'view' => 0,
  ),
  'fieldMeta' => 
  array (
    'entrydata' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'entryname' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'view' => 
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
    'View' => 
    array (
      'class' => 'View',
      'local' => 'view',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
