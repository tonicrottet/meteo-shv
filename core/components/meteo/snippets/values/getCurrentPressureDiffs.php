<?php
/*
** getCurrentPressureDiffs
**
** processes the current measurements
**
** and tpl as params
**
** the stations are given by a comma separated list
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

	$stationsarr = explode('-', $stations);

	for($i = 0; $i < $modx->getOption('meteovaluessteps',null, 4); $i++) {

		$diff = floatval($m->getvalue($stationsarr[0], curMeasurementValues::QFF, $i)) - 
			floatval($m->getvalue($stationsarr[1], curMeasurementValues::QFF, $i));

		$diff = round($diff, 1);

		$datetime = $m->getvaluearray($stationsarr[0], curMeasurementValues::QFF, $i);

		$diffdatetime = $datetime["datetime"];

		$props = array(
			'diff' => $diff,
			'diffdatetime' => $diffdatetime
		);

		$out .= $modx->getChunk($tpl, $props);
	}

	return $out;

}