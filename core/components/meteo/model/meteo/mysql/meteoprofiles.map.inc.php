<?php
$xpdo_meta_map['MeteoProfiles']= array (
  'package' => 'meteo',
  'version' => '1.1',
  'table' => 'meteo_profiles',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'standard' => 0,
    'name' => '',
    'mode' => '',
    'owner' => NULL,
    'menu' => 0,
    'view' => 0,
    'settings' => NULL,
  ),
  'fieldMeta' => 
  array (
    'standard' => 
    array (
      'dbtype' => 'int',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'mode' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'owner' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'unique',
    ),
    'menu' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'view' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'settings' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'owner' => 
    array (
      'alias' => 'owner',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'owner' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
        'standard' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'owner_2' => 
    array (
      'alias' => 'owner_2',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'owner' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
);
