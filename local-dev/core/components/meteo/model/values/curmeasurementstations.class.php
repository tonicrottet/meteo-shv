<?php
/**
 * curMeasurementStations
 *
 */
/**
 * Represents a set of Data which stands for the current Measurement Data
 *
 */

class curMeasurementStations implements Iterator {

	private $_stations = array();

    function __construct() {
    	// stations.csv contains the data for this
    	$stationlines = file('stations.csv', FILE_USE_INCLUDE_PATH);

    	foreach ($stationlines as $stationline) {
    		$stationdata = explode('|', $stationline);
    		$this->_stations[$stationdata[0]] = new curMeasurementStation($stationdata);
    	}

    }

	public function get($name) {
		return $this->_stations[$name];
	}

    public function rewind() {
        return reset($this->_stations);
    }


    public function current() {
        return current($this->_stations);
    }

    public function key() {
        return key($this->_stations);
    }

    public function next() {
        return next($this->_stations);
    }

    public function valid() {
        return key($this->_stations) !== null;
    }
}
