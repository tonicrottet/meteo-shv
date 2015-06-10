<?php
/*
** testingSnippet
**
** testing Purposes
**
**
*/


// Melde alle PHP Fehler (siehe Changelog)
ini_set('display_errors', 'On');
ini_set('html_errors', 0);



$winddirs = array("N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW");

	$out = '';

	for($i = 0; $i < 360; $i++) {

		$w = floatval($i);
		$w = intval(abs(round($w / 22.5)));
		$windir = $winddirs[$w];

		$out .= $i . ": " . $winddir . "<br>";

	}

return $out;