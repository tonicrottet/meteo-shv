<?php
$xpdo_meta_map['ShvmeteoUserTokens']= array (
  'package' => 'meteo',
  'version' => '1.1',
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
      'index' => 'unique',
    ),
    'token' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => true,
      'index' => 'index',
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
    'shvmeteouser' => 
    array (
      'alias' => 'shvmeteouser',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'SHVMeteoUser' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'token' => 
    array (
      'alias' => 'token',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'token' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
);
