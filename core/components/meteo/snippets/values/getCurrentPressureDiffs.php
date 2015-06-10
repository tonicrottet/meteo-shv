<?php
/*
** getCurrentPressureDiffs
**
** processes the current measurements
**
** outertpl and tpl as params
**
** the stations are given by a comma separated list
**
*/

$model_path = $modx->getOption('core_path').'components/meteo/model/';

if(!$modx->loadClass(
	'values.curMeasurementValues',
	$model_path,
	false, 
	true)) {
	return "ERROR";
} else {

	//$cmvs = new curMeasurementValues($modx);

	//return $cmvs->get("INT", "FOEHN");
}