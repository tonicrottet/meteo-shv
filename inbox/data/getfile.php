<?php

// check passphrase

//modx intinitalizing
require_once '../../manager/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');
 
// API Mode
define('MODX_API_MODE', true);

if(!$_GET['q'] || $_GET['q'] == 'getfile.php') {
	header("HTTP/1.1 403 Unauthorized" );
	exit;
}


// check session login
if (!isset($_SESSION['meteouserid']))
{
	header("HTTP/1.1 403 Unauthorized" );
	exit;
}

$name = $_GET['q'];
$fp = fopen($name, 'rb');

$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
$mimetype = finfo_file($finfo, $filename);
finfo_close($finfo);

header("Content-Type: ". $mimetype);
header("Content-Length: " . filesize($name));


fpassthru($fp);