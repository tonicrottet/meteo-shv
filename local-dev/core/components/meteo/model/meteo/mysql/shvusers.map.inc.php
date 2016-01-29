<?php
$xpdo_meta_map['ShvUsers']= array (
  'package' => 'meteo',
  'version' => '1.1',
  'table' => 'shv_users',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'nr' => '',
    'art' => '',
    'password' => '',
    'step' => '',
    'email' => '',
  ),
  'fieldMeta' => 
  array (
    'nr' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'art' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'password' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'step' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'email' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
  ),
);
