<?php

/*
**
** checkshvusers
**
**
*/

ignore_user_abort(1); // run script in background
set_time_limit(0); // run script forever 

//modx intinitalizing
require_once '../manager/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');
 
// API Mode
define('MODX_API_MODE', true);

echo "checking table modx_shv_users<br>";


$message = "";

//adding package
$package_path = $modx->getOption('core_path').'components/meteo/model/';

if(!$modx->addPackage('meteo', $package_path)) {
    echo "coulnd load package<br>";
    die();
}

$table = $modx->getTableName('SHVUser');

$sql = "SELECT COUNT(*) as num FROM {$table}";
$result = $modx->query($sql);

if (!is_object($result)) {
    $message .= 'No result!';
} else {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $numResults = $row['num'];
    $message .= 'Count:' . $numResults;
}

echo $message;
echo "<br>";


$modx->getService('mail', 'mail.modPHPMailer');
$modx->mail->set(modMail::MAIL_BODY, $message);
$modx->mail->set(modMail::MAIL_FROM, 'toni@azoom.ch');
$modx->mail->set(modMail::MAIL_FROM_NAME, 'Toni Crottet');
$modx->mail->set(modMail::MAIL_SUBJECT,'Debug Cronjob');
$modx->mail->address('to', 'toni@azoom.ch');
$modx->mail->address('reply-to', 'toni@azoom.ch');
$modx->mail->setHTML(true);
if (!$modx->mail->send()) {
    $modx->log(modX::LOG_LEVEL_ERROR,'An error occurred while trying to send the email: '.$modx->mail->mailer->ErrorInfo);
}
$modx->mail->reset();

echo "Email sent"

?>

