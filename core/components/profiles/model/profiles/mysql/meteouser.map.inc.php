<?php
$xpdo_meta_map['MeteoUser']= array (
  'package' => 'profiles',
  'version' => NULL,
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
  'composites' => 
  array (
    'Profiles' => 
    array (
      'class' => 'Profile',
      'local' => 'id',
      'foreign' => 'profile',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
