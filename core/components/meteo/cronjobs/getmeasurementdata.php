<?php

/*
**
** getmeasurementdata
**
** gets the measurement data
**
**
*/

// Melde alle PHP Fehler (siehe Changelog)
ini_set('display_errors', 'On');
ini_set('html_errors', 0);

error_reporting(E_ALL);


//modx intinitalizing
require_once '../../../../manager/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');
 
// API Mode
define('MODX_API_MODE', true);

// get options
$datappath = $modx->getOption('meteovaluesdatapath',null, $modx->getOption('assets_path').'components/meteo/values/');

$model_path = $modx->getOption('core_path').'components/meteo/model/';

if(!$modx->loadClass(
	'values.curMeasurementValues',
	$model_path,
	false, 
	true)) {
	return "ERROR";
} else {

	$cmvs = new curMeasurementValues($modx);
	echo $cmvs->refreshFiles();

}

echo "DONE";