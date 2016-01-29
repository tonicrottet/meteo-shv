<?php
/*
** listStations
**
** lists Stations for a Select Box, Format: TITLE1==VALUE1||TITLE2==VALUE2
**
*/

$corePath = $modx->getOption('meteo.core_path',null,$modx->getOption('core_path').'components/meteo/');
$modelPath = $corePath . "model/";


if(!$modx->loadClass(
	'values.curMeasurementValues',
	$modelPath,
	false, 
	true)) {
	return "ERROR";
} else {

	$output = '';

	$m = new curMeasurementValues($modx);
	$stations = $m->getstations();

	$stationvalues = array();

	foreach ($stations as $station) {
		$stationarray[] = $station->get(curMeasurementStation::STNNAME) . " (" .$station->get(curMeasurementStation::STNABR) . ")==" . $station->get(curMeasurementStation::STNABR);	
	}

	asort($stationarray);

	$output = implode("||", $stationarray);

	return $output;

}