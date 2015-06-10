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
    private $modx= null;
    private $config = array();
    private $stations = null;
    private $datafiles = array();

    const TEMPERATURE = 'TEMPERATURE';
    const SUNSHINE    = 'SUNSHINE';
    const RAIN        = 'RAIN';
    const WINDDIR     = 'WINDDIR';
    const WINDMEAN    = 'WINDMEAN';
    const QNH         = 'QNH';
    const WINDMAX     = 'WINDMAX';
    const HUMIDITY    = 'HUMIDITY';
    const QFE         = 'QFE';
    const QFF         = 'QFF';

    /**
     * @param xPDO $modx A reference to the modX|xPDO instance
     */
    function __construct(xPDO &$modx, array $config = array()) {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('meteo.core_path',null,$this->modx->getOption('core_path').'components/meteo/');
        $assetsPath = $this->modx->getOption('meteo.assets_path',null,$this->modx->getOption('assets_path').'components/meteo/');
        $datafileurl = $this->modx->getOption('datafileurl',null,'http://data.geo.admin.ch.s3.amazonaws.com/ch.meteoschweiz.swissmetnet/VQHA69.txt');
        $modelPath = $corePath . "model/";
        $dataPath = $modx->getOption('meteovaluesdatapath',null, $modx->getOption('assets_path').'components/meteo/values/');
        $steps = $modx->getOption('meteovaluessteps',null, 4);

        $this->config = array_merge(
            array(
                'corePath' => $corePath,
                'assetsPath' => $assetsPath, 
                'datafileurl' => $datafileurl, 
                'modelPath' => $modelPath, 
                'dataPath' => $dataPath, 
                'steps' => $steps),
            $config);

        if (!$this->modx->loadClass('values.curMeasurementValueFile',$this->config['modelPath'],false,true)) {
            return false;
        }

        if (!$this->modx->loadClass('values.curMeasurementStations',$this->config['modelPath'],false,true)) {
            return false;
        }

        if (!$this->modx->loadClass('values.curMeasurementStation',$this->config['modelPath'],false,true)) {
            return false;
        }

        $this->loadata();

    }


    /**
     * loads the measurement data from the datapath
     * @return string
     */
    private function loadata() {
        // load the files
        $this->datafiles = array();

        foreach (glob($this->config['dataPath'] . "*.val") as $filename) {
            $this->datafiles[] = new curMeasurementValueFile($filename);
        }

        // sort the files
        usort($this->datafiles, function($a, $b) {
            $ad = $a->measuretime();
            $bd = $b->measuretime();

            if($ad == $bd) {
                return 0;
            }

            return $ad < $bd ? 1 : -1;
        });
    }


    /**
     * returns true if successful
     * @return output buffer for debug or false if not successful
     */
    public function refreshFiles() {
        $out = "Refresh called on " . date("d.m.Y H:i:s") . "<br>";

        // load the one from the web
        $current = new curMeasurementValueFile($this->config['datafileurl'], true);

        // check if the new one already has been stored  
        foreach ($this->datafiles as $datafile) {
            if($current->rawmeasuretime() == $datafile->rawmeasuretime()) {
                // no news, there is already a file with the same date!!
                // ---> return then
                $out .= "current measure from " . $current->measuretime()->format("d.m.Y H:i:s") . "<br>";
                $out .= "no new file added <br>";

                return $out;
            }
        }

        // got here, we have a new file
        // save the current and sort the files
    
        $current->savetofile($this->config['dataPath'] . $current->rawmeasuretime() . ".val");
        $out .= "File saved <br>";

        $this->datafiles[] = $current;

        // sort the files
        usort($this->datafiles, function($a, $b) {
            $ad = $a->measuretime();
            $bd = $b->measuretime();

            if($ad == $bd) {
                return 0;
            }

            return $ad < $bd ? 1 : -1;
        });

        // check needed steps
        if(count($this->datafiles) <= $this->config['steps']) {
            $out .= "Number of required Steps: " . $this->config['steps'] . " and number of data files: " . count($this->datafiles) . "<br>";
            return $out;
        }

        // remove older needed steps
        for($i = $this->config['steps']; $i < count($this->datafiles); $i++) {
            $out .= "removeing old file " . $this->datafiles[$i]->rawmeasuretime() . ".val <br>";
            unlink($this->datafiles[$i]->file);
            unset($this->datafiles[$i]);
        }

        return $out;

    }

    /**
     * Returns a value on a given station, valuename and step
     * @return string
     */
    public function getvalue($station, $value, $step = 0) {
        if(count($this->datafiles) <= $step) {
            return false;
        }

        return $this->datafiles[$step]->getvalue($station, $value);
    }

    /**
     * Returns a value on a given station, valuename and step
     * @return array("value", "unit", "datetime")
     */
    public function getvaluearray($station, $value, $step = 0) {
        if(count($this->datafiles) <= $step) {
            return false;
        }

        return $this->datafiles[$step]->getvaluearray($station, $value);
    }

    /**
     * Returns the stations object
     * @return string
     */
    public function getstations() {
        if(!$stations) {
            $this->stations = new curMeasurementStations();
        }

        return $this->stations;
    }


}
