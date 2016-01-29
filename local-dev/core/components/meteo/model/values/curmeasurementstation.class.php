<?php
/**
 * curMeasurementStation
 *
 */
/**
 * Represents a set of Data which stands for the current Measurement Data
 *
 */

class curMeasurementStation {

	const STNNAME             = "name";
	const STNABR              = "abr";
	const STNKOORDS           = "koords";
	const STNKOORDS_SWISSGRID = "koords_swissgrid";
	const STNALT              = "altitude";

	private $_rawdata = array();
	private $_data = array();


    function __construct($data) {
    	$this->_rawdata = $data;

    	$this->_data = array(
	    		self::STNABR => $data[0],
	    		self::STNNAME => $data[1],
	    		self::STNKOORDS => $data[2],
	    		self::STNKOORDS_SWISSGRID => $data[3],
	    		self::STNALT => $data[4]
    		);
    }

    public function get($value) {
    	return $this->_data[$value];
    }

	public function toArray() {
		return $this->_data;
	}
}
