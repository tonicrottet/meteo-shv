<?php
/**
 * curMeasurementValues
 *
 */
/**
 * Represents a set of Data which stands for the current Measurement Data
 *
 */
class curMeasurementValues {
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx= null;
    
    /**
     * @param xPDO $modx A reference to the modX|xPDO instance
     */
    function __construct(xPDO &$modx) {
        $this->modx =& $modx;
    }

    /**
     * Returns true if the parser is currently processing an uncacheable tag
     * @return bool
     */
    public function fetchData() {

    }

    /**
     * Returns a value on a given station, valuename and step
     * @return string
     */
    public function get($station, $value, $step = 1) {
        return $station . " - " . $value;
    }


}
