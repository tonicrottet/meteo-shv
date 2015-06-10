<?php
$xpdo_meta_map['SHVMeteoUserToken']= array (
  'package' => 'meteo',
  'version' => NULL,
  'table' => 'shvmeteo_user_tokens',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'SHVMeteoUser' => NULL,
    'token' => NULL,
    'verifier' => '',
    'expires' => NULL,
  ),
  'fieldMeta' => 
  array (
    'SHVMeteoUser' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'token' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'integer',
      'null' => true,
    ),
    'verifier' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'expires' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'token' => 
    array (
      'alias' => 'token',
      'primary' => false,
      'unique' => false,
      'columns' => 
      array (
        'token' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'SHVMeteoUser' => 
    array (
      'alias' => 'SHVMeteoUser',
      'primary' => false,
      'unique' => false,
      'columns' => 
      array (
        'SHVMeteoUser' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
