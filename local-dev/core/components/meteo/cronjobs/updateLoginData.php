<?php

/*
**
** updateLoginData
**
** reads the Login Data file into the DB
** takes first the 
**
** returns the string to select the form input
**
*/


// check passphrase
if( $_GET['runexec'] !== 'EEC4CDFBE141AA75C1B272EA575E8') die('success');


ignore_user_abort(1); // run script in background
set_time_limit(0); // run script forever 

//modx intinitalizing
require_once '../../../../manager/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');
 
// API Mode
define('MODX_API_MODE', true);


$loginmethod = $modx->getOption('loginmethod', null, 'csvimport');

switch ($loginmethod) {

    case 'online':
        //nothing to do here
        echo "Login Method set to $loginmethod<br>";
        echo "Nothing to do...";
        break;

    // Standart Login Method
    case 'csvimport':
    default:

        // since the creation of the CSV at the server can be right at the same time, postpone execution of this script
        sleep(30);

        // define some variables
        $local_file = $modx->getOption('loginftpfile');
        $server_file = $modx->getOption('loginftpfile');
        $ftp_server = $modx->getOption('loginftpserver');
        $ftp_user_name = $modx->getOption('loginftpuser');
        $ftp_user_pass = $modx->getOption('loginftppassword');


        echo "Connecting $ftp_server<br>";
        $conn_id = ftp_connect($ftp_server);
        ftp_set_option($conn_id, FTP_TIMEOUT_SEC, 120);


        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        // try to download $server_file and save to $local_file
        if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
            echo "Successfully written<br>";
        }
        else {
            echo "There was a problem<br>";
            return;
        }
        // close the connection
        ftp_close($conn_id);


        echo "Writing Data into DB<br>";


        //adding package
        $package_path = $modx->getOption('core_path').'components/meteo/model/';

        if(!$modx->addPackage('meteo', $package_path)) {
            echo "coulnd load package<br>";
            die();
        }

        $table = $modx->getTableName('SHVUser');
        $sql = "TRUNCATE TABLE {$table}";

        echo "deleteing TABLE $table<br>";

        $result = $modx->query($sql);

        // count rows debugwise
        $fp = file($local_file);
        $filerowcount = count($fp);
        $filerowcount++;
        echo "filerowcount: $filerowcount<br>";
        $modx->log(xPDO::LOG_LEVEL_ERROR, "filerowcount: $filerowcount", '', 'updateLoginData');
        unset($fp);


        // preparing mass insert
        try {

        $sql = "INSERT INTO {$table} (id, nr, art, password, step, email) VALUES (?, ?, ?, ?, ? ,?)";
        $q = $modx->prepare($sql);



        $row = 1;
        $retvalues = 0;
        if (($handle = fopen($local_file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        	    $q->bindValue(1, $data[0]);
        	    $q->bindValue(2, $data[1]);
        	    $q->bindValue(3, $data[2]);
        	    $q->bindValue(4, $data[3]);
        	    $q->bindValue(5, $data[4]);
        	    $q->bindValue(6, $data[5]);
        	    if(!$q->execute()) {
                    $retvalues++;
                }
                $row++;
            }
            fclose($handle);
        }


        } catch (Exception $e) {
            echo $e->getMessage();
            $modx->log(xPDO::LOG_LEVEL_ERROR,$e->getMessage(),'','updateLoginData');
        }


        echo "$row inserted<br>";
        $modx->log(xPDO::LOG_LEVEL_ERROR,"$row inserted",'','updateLoginData');
        $modx->log(xPDO::LOG_LEVEL_ERROR,"$retvalues returned FALSE",'','updateLoginData');

        /*
        echo "moveing the file<br>";
        rename($local_file, dirname ($local_file) . "/" . date("Y-m-d-H-i-s") . ".csv");
        */

        echo "deleting input file<br>";
        unlink($local_file);

        echo "optimizing table";
        $modx->query("OPTIMIZE TABLE {$table}");

    break;
}

?>

