<?php
/*
** getAreaSummary
**
** processes the current measurements
**
** tplwindouter, tplwindrow, tplpressurediffouter, tplpressurediffrow as params
**
** further we get
** - windstations 
** - pressurediffstations
**
** both, commaseparated lists
**
*/

$corePath = $modx->getOption('meteo.core_path',null,$modx->getOption('core_path').'components/meteo/');
$modelPath = $corePath . "model/";

$steps = $modx->getOption('meteovaluessteps',null, 4);


if(!$modx->loadClass(
	'values.curMeasurementValues',
	$modelPath,
	false, 
	true)) {
	return "ERROR";
} else {
	$out = '';
	$m = new curMeasurementValues($modx);


	// get Wind stations
	$windstations = explode(',', $windstations);

	$winddirs = array("N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW", "N");

	foreach ($windstations as $windstation) {

		$parsedvalues = '';

		for($i = 0; $i < $steps; $i++) {
			$windmean = $m->getvalue($windstation, curMeasurementValues::WINDMEAN, $i);
			$windmax = $m->getvalue($windstation, curMeasurementValues::WINDMAX, $i);
			$winddirval = $m->getvalue($windstation, curMeasurementValues::WINDDIR, $i);
			$humidity = $m->getvalue($windstation, curMeasurementValues::HUMIDITY, $i);
			$temperature = $m->getvalue($windstation, curMeasurementValues::TEMPERATURE, $i);
			
			$w = floatval($winddirval);
			$w = intval(abs(round($w / 22.5)));
			$windir = $winddirs[$w];

			$valarray = $m->getvaluearray($windstation, curMeasurementValues::WINDMEAN, $i);
			$datetime = $valarray["datetime"];

			$props = array(
				'windmean' => $windmean,
				'windmax' => $windmax,
				'winddirval' => $winddirval,
				'windir' => $windir,
				'humidity' => $humidity,
				'temperature' => $temperature,
				'datetime' => $datetime
				);

			$parsedvalues .= $modx->getChunk($tplwindrow, $props);
		}

		$curstation = $m->getstations()->get($windstation);


		$props = array(
			'stationname' => $curstation->get(curMeasurementStation::STNNAME),
			'stationabr' => $windstation, 
			'stationalt' => $curstation->get(curMeasurementStation::STNALT),
			'stationvalues' => $parsedvalues
			);

		$out .= $modx->getChunk($tplwindouter, $props);
	}

	$pressurediffstations = explode(',', $pressurediffstations);

	foreach ($pressurediffstations as $pressurediffstation) {

		$stationsarr = explode('-', $pressurediffstation);
		$parsedvalues = '';
		for($i = 0; $i < $steps; $i++) {

			$diff = floatval($m->getvalue($stationsarr[0], curMeasurementValues::QFF, $i)) - 
				floatval($m->getvalue($stationsarr[1], curMeasurementValues::QFF, $i));

			$diff = round($diff, 1);

			$datetime = $m->getvaluearray($stationsarr[0], curMeasurementValues::QFF, $i);

			$diffdatetime = $datetime["datetime"];

			$props = array(
				'diff' => $diff,
				'datetime' => $diffdatetime
			);

			$parsedvalues .= $modx->getChunk($tplpressurediffrow, $props);
		}

		$curstation1 = $m->getstations()->get($stationsarr[0]);
		$curstation2 = $m->getstations()->get($stationsarr[1]);



		$props = array(
			'stationname1' => $curstation1->get(curMeasurementStation::STNNAME),
			'stationabr1' => $stationsarr[0], 
			'stationalt1' => $curstation1->get(curMeasurementStation::STNALT),
			'stationname2' => $curstation2->get(curMeasurementStation::STNNAME),
			'stationabr2' => $stationsarr[1], 
			'stationalt2' => $curstation2->get(curMeasurementStation::STNALT),
			'stationvalues' => $parsedvalues
			);

		$out .= $modx->getChunk($tplpressurediffouter, $props);

	}



	return $out;


}