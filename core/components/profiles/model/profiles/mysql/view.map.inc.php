<?php
$xpdo_meta_map['View']= array (
  'package' => 'profiles',
  'version' => NULL,
  'table' => 'meteo_views',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
  ),
  'fieldMeta' => 
  array (
  ),
  'composites' => 
  array (
    'ViewEntry' => 
    array (
      'class' => 'ViewEntry',
      'local' => 'id',
      'foreign' => 'viewentry',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
