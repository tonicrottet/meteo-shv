<?php

/*
**
** testcurvalues
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
	echo "Interlaken: WINDMEAN<br>";
	echo implode(', ', $cmvs->getvaluearray("INT", curMeasurementValues::WINDMEAN, 0)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("INT", curMeasurementValues::WINDMEAN, 1)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("INT", curMeasurementValues::WINDMEAN, 2)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("INT", curMeasurementValues::WINDMEAN, 3)) . "<br>";

	echo "<br>";
	echo "WINDGUSTS<br>";
	echo implode(', ', $cmvs->getvaluearray("INT", curMeasurementValues::WINDMAX, 0)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("INT", curMeasurementValues::WINDMAX, 1)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("INT", curMeasurementValues::WINDMAX, 2)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("INT", curMeasurementValues::WINDMAX, 3)) . "<br>";

	echo "<br>";
	echo "PRESSURE<br>";
	echo implode(', ', $cmvs->getvaluearray("INT", curMeasurementValues::QFF, 0)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("INT", curMeasurementValues::QFF, 1)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("INT", curMeasurementValues::QFF, 2)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("INT", curMeasurementValues::QFF, 3)) . "<br>";

	echo "<br>";
	echo "Thun: WINDMEAN<br>";
	echo implode(', ', $cmvs->getvaluearray("THU", curMeasurementValues::WINDMEAN, 0)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("THU", curMeasurementValues::WINDMEAN, 1)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("THU", curMeasurementValues::WINDMEAN, 2)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("THU", curMeasurementValues::WINDMEAN, 3)) . "<br>";

	echo "<br>";
	echo "WINDGUSTS<br>";
	echo implode(', ', $cmvs->getvaluearray("THU", curMeasurementValues::WINDMAX, 0)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("THU", curMeasurementValues::WINDMAX, 1)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("THU", curMeasurementValues::WINDMAX, 2)) . "<br>";
	echo implode(', ', $cmvs->getvaluearray("THU", curMeasurementValues::WINDMAX, 3)) . "<br>";



	$stations = $cmvs->getstations();

	$stationvalues = array();

	foreach ($stations as $station) {
		$stationarray[] = $station->get(curMeasurementStation::STNNAME); // . "==" . $station->get(curMeasurementStation::STNABR);	
	}

	echo implode("||", $stationarray);

}
