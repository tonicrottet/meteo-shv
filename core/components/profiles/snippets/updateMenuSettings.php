<?php
/*
** updateMenuSettings
**
** takes the POST vars and updates the Settings
**
** redirects to the closets sibling or back to "Home" in case none found
*/

$package_path = $modx->getOption('core_path').'components/profiles/model/';

if(!$modx->addPackage('profiles', $package_path)) {
    return 'Konnte Package nicht laden';
}

// setup Profile
if (isset($_SESSION['profileid']))
{
	$profileid = $_SESSION['profileid'];
	$profile = $modx->getObject('Profile', $profileid); 

	// get POST vars
	$resid = $_REQUEST['curpageid'];

	$easymode = $_POST['easymode'];
	$period1 = (int)$_REQUEST['period1'];
	$period2 = (int)$_REQUEST['period2'];
	$period3 = (int)$_REQUEST['period3'];

	$period = $period1 + $period2 + $period3;

	$profile->set('settings', "easymode=$easymode,period=$period");

	// Set new Menu
	$newmenu = 'std-' . $modx->getOption('cultureKey') . "-$easymode-$period";

	$menu = $modx->getObject('Menu', array('name'=>$newmenu));

	if($menu !== NULL) {
		$profile->set('menu', $menu->get('id'));
	}

	$profile->save();

	$url = $modx->makeUrl($resid);
	$modx->sendRedirect($url);
} else // no profile set, just return
{
	$url = $modx->makeUrl($modx->getOption('site_start'));
	$modx->sendRedirect($url);
}