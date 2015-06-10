<?php
/**
 * curMeasurementValueFile
 *
 */
class curMeasurementValueFile {

  /*
  ** definitions of the values
  **

      Parameter:
      ----------
                               Einheit                  Beschreibung
      tre200s0                 ∞C                       Lufttemperatur 2 m ¸ber Boden; Momentanwert
      sre000z0                 min                      Sonnenscheindauer; Zehnminutensumme
      rre150z0                 mm                       Niederschlag; Zehnminutensumme
      dkl010z0                 ∞                        Windrichtung; Zehnminutenmittel
      fu3010z0                 km/h                     Windgeschwindigkeit; Zehnminutenmittel
      pp0qnhs0                 hPa                      Luftdruck reduziert auf Meeresniveau mit Standardatmosph‰re (QNH); Momentanwert
      fu3010z1                 km/h                     Bˆenspitze (Sekundenbˆe); Maximum
      ure200s0                 %                        Relative Luftfeuchtigkeit 2 m ¸ber Boden; Momentanwert
      prestas0                 hPa                      Luftdruck auf Stationshˆhe (QFE); Momentanwert
      pp0qffs0                 hPa                      Luftdruck reduziert auf Meeresniveau (QFF); Momentanwert

  */
  const TEMPERATURE      = 'tre200s0';
  const SUNSHINE         = 'sre000z0';
  const RAIN             = 'rre150z0';
  const WINDDIR          = 'dkl010z0';
  const WINDMEAN         = 'fu3010z0';
  const QNH              = 'pp0qnhs0';
  const WINDMAX          = 'fu3010z1';
  const HUMIDITY         = 'ure200s0';
  const QFE              = 'prestas0';
  const QFF              = 'pp0qffs0';

  const TEMPERATURE_UNIT = '°C';
  const SUNSHINE_UNIT    = 'min';
  const RAIN_UNIT        = 'mm';
  const WINDDIR_UNIT     = '°';
  const WINDMEAN_UNIT    = 'km/h';
  const QNH_UNIT         = 'hPa';
  const WINDMAX_UNIT     = 'km/h';
  const HUMIDITY_UNIT    = '%';
  const QFE_UNIT         = 'hPa';
  const QFF_UNIT         = 'hPa';

  private $data = array();
  private $units = array();
  private $zuludatetime;
  private $localdatetime;
  private $timestring;
  private $rawdata;

  public $file;

  function __construct($url, $storeraw = false) {
    $this->file = $url;
    $this->data = $this->parsemeteofile($url);

    if($storeraw) {
      $this->rawdata = file_get_contents($url);
    }

    // setup time stamp
    $line = reset($this->data);
    $this->timestring = $line['time'];

    $this->zuludatetime = new DateTime($this->timestring, new DateTimeZone('UTC'));
    $this->localdatetime = clone $this->zuludatetime;
    $this->localdatetime->setTimeZone(new DateTimeZone('Europe/Zurich'));
  }

  public function measuretime($utc = false) {
    if($utc) {
      return $this->zuludatetime;
    } else {
      return $this->localdatetime;
    }
  }

  public function rawmeasuretime() {
    return $this->timestring;
  }

  private function mapunitfield($field) {
    switch ($field) {
      case curMeasurementValues::TEMPERATURE:
        return self::TEMPERATURE_UNIT;
        break;

      case curMeasurementValues::SUNSHINE:
        return self::SUNSHINE_UNIT;
        break;

      case curMeasurementValues::RAIN:
        return self::RAIN_UNIT;
        break;

      case curMeasurementValues::WINDDIR:
        return self::WINDDIR_UNIT;
        break;

      case curMeasurementValues::WINDMEAN:
        return self::WINDMEAN_UNIT;
        break;

      case curMeasurementValues::QNH:
        return self::QNH_UNIT;
        break;

      case curMeasurementValues::WINDMAX:
        return self::WINDMAX_UNIT;
        break;

      case curMeasurementValues::HUMIDITY:
        return self::HUMIDITY_UNIT;
        break;

      case curMeasurementValues::QFE:
        return self::QFE_UNIT;
        break;

      case curMeasurementValues::QFF:
        return self::QFF_UNIT;
        break;

    }
  }

  private function mapfield($field) {
    switch ($field) {
      case curMeasurementValues::TEMPERATURE:
        return self::TEMPERATURE;
        break;

      case curMeasurementValues::SUNSHINE:
        return self::SUNSHINE;
        break;

      case curMeasurementValues::RAIN:
        return self::RAIN;
        break;

      case curMeasurementValues::WINDDIR:
        return self::WINDDIR;
        break;

      case curMeasurementValues::WINDMEAN:
        return self::WINDMEAN;
        break;

      case curMeasurementValues::QNH:
        return self::QNH;
        break;

      case curMeasurementValues::WINDMAX:
        return self::WINDMAX;
        break;

      case curMeasurementValues::HUMIDITY:
        return self::HUMIDITY;
        break;

      case curMeasurementValues::QFE:
        return self::QFE;
        break;

      case curMeasurementValues::QFF:
        return self::QFF;
        break;

    }
  }


  public function getvalue($station, $field) {
    if($this->data[$station]) {

      $valuefield = $this->mapfield($field);

      return $this->data[$station][$valuefield] ? $this->data[$station][$valuefield] : false;
    }

    return false;
  }

  public function getvaluearray($station, $field) {
    if($this->data[$station]) {

      $valuefield = $this->mapfield($field);

      $out = array();

      $out['value'] = $this->data[$station][$valuefield] ? $this->data[$station][$valuefield] : false;
      $out['unit'] = $this->mapunitfield($field);
      $out['datetime'] = $this->localdatetime->format("d.m.Y H:i:s");



      return $out;

    }

    return false;
  }


  public function savetofile($file) {
    $this->file = $file;
    return file_put_contents($file, $this->rawdata);
  }

	private function parsemeteofile($url) {
   		// first two lines we don't need, then the third is for the value fields and the first field is the
   		// station name
   		// 
   		// returns an array which can be $data[station][value]

		$lines = file($url);

		$fields = explode('|', rtrim($lines[2]));

		$datalines = array();

		for($i = 3; $i < count($lines); $i++) {
			$datalines[] = rtrim($lines[$i]);
		}

		$data = array();

		foreach($datalines as $dataline) {
			$curdata = explode('|', $dataline);
			$data[$curdata[0]] = array_combine($fields, $curdata);
		}

		return $data;
	}

}
