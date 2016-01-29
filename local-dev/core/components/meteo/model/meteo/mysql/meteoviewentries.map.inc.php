<?php
$xpdo_meta_map['MeteoViewentries']= array (
  'package' => 'meteo',
  'version' => '1.1',
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
);
