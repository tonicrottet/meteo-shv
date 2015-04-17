<?php
/*
** prepareSession
**
** prepares a Session and sets placeholder accordingly
**
** uses SESSION vars: meteouserid, profileid, menuid
** in case a profile or a menu changes, unset the SESSION vars also
**
** Following placeholders are set:
**  - meteouserid
**  - shvnr
**  - meteousername
**
**  - profileid
**  - profilename
**  - progilemode
** 
**  - menuid
**  - menudata
*/
// set debug mode
$debugmode = $modx->getOption('meteo-debugmode');

if($debugmode) $modx->setLogLevel(4);

$modx->log(modX::LOG_LEVEL_DEBUG
    , '[prepareSession] called on page '. $modx->resource->id . ' with the following properties: '
    .print_r($scriptProperties, true));


$package_path = $modx->getOption('core_path').'components/profiles/model/';

if(!$modx->addPackage('profiles', $package_path)) {
    return 'Konnte Package nicht laden';
}



// check UserID
if (isset($_SESSION['meteouserid']))
{
	$meteouserid = $_SESSION['meteouserid'];
	$meteouser = $modx->getObject('MeteoUser', $meteouserid);
} else 
{
	// run logindelegate
	$logindelegate = $modx->getOption('logindelegate');
	$meteouserid = $modx->runSnippet($logindelegate);

	// got here, meteouserid is valid then
	if($meteouserid) {
		$meteouser = $modx->getObject('MeteoUser', $meteouserid);
		$_SESSION['meteouserid'] = $meteouserid;
	} else { 
		//invalidate session
		unset($_SESSION['meteouserid']);
		return;
	}
}

// from here, $meteouser and $meteouserid are validated

// set Placeholders for Meteouser
$modx->setPlaceholder('meteouserid', $meteouserid);
$modx->setPlaceholder('shvnr', $meteouser->get('shvnr'));
$modx->setPlaceholder('meteousername', $meteouser->get('name'));


// setup Profile
if (isset($_SESSION['profileid']))
{
	$profileid = $_SESSION['profileid'];
	$profile = $modx->getObject('Profile', $profileid);
} else 
{
	// get standard profile
	$profile = $modx->getObject('Profile', array('owner' => $meteouserid, 'standard' => 1));

	if($profile === NULL) {
		// no profile for this user, create one
		$profile = $modx->newObject('Profile');

		$profile->set('name', $meteouser->get('shvnr') . '-' . $meteouser->get('name') . '-1');
		$profile->set('standard', 1);
		$meteouser->addMany($profile);

		$menuname = 'std-' . $modx->getOption('cultureKey') . '-' . $modx->getOption('stdmenuname');

		$menu = $modx->getObject('Menu', array('name' => $menuname));

		$profile->set('menu', $menu->get('id'));
		$profile->set('owner', $meteouserid);

		$meteouser->save();

		$_SESSION['profileid'] = $profileid;
	}

	$profileid = $profile->get('id');
	$_SESSION['profileid'] = $profileid;
}

// from here, $profile and $profileid are validated

// check settings
if(strlen($meteouser->get('settings')) == 0) {
	$meteouser->set('settings', $modx->getOption('stdprofilesettings'));
	$meteouser->save();
}

// set Placeholders for Profiles
$modx->setPlaceholder('profileid', $profileid);
$modx->setPlaceholder('profilename', $profile->get('name'));
$modx->setPlaceholder('profilemode', $meteouser->get('mode'));
$modx->setPlaceholder('profilesettings', $profile->get('settings'));


// setup Menu
$menu = $modx->getObject('Menu', $profile->get('menu'));
$menuid = $menu->get('id');
$menuname = $menu->get('name');

// check if this is a std-menu and correspond to the actual language
// since the standard menus are multilingual the menus are marked "std-<lang>-<menucode>"
if(0 === strpos($menuname, 'std-')) {
	$namefragments = explode('-', $menuname);
	$curlang = $modx->getOption('cultureKey');
	
	// fetch the corresponing menu in the other language
	if($namefragments[1] != $curlang) {
		$modx->log(modX::LOG_LEVEL_DEBUG, '[prepareSession] change language to '. $curlang);

		$namefragments[1] = $curlang;
		$menuname = implode('-', $namefragments);

		//repeat fetching menu params useing the new name
		$menu = $modx->getObject('Menu', array('name' => $menuname));
		$menuid = $menu->get('id');
	}
}


// Checck Menu Cache inside DB (Note: Caching inside SESSION is risky, since it can change during a session)
$menudata = $menu->get('menucache');

if(strlen($menudata) == 0) {

	// get generator
	$generator = $menu->get('generator');
	$generator = strlen($generator) == 0 ? $modx->getOption('stdmenugenerator') : $generator;

	// generate menu data by the generator
	$menudata = $modx->runSnippet(
			$menugeneratorprefix . $generator, 
			array('menuid' => $menuid));

	// store generated menudata


	$menu->set('menucache', $menudata);

	if ($menu->save() == false) {
    	$modx->error->checkValidation($object);
    	$modx->log(modX::LOG_LEVEL_DEBUG, '[prepareSession] ' . $modx->lexicon($objectType.'_err_save'));
	}	

	$menugen = 'generated from ' . $menugeneratorprefix . $generator;
} else {
	$menugen = 'from DB Cache';
}

// here, $menuid and $menudata are validated

// set Placeholders for Menus
$modx->setPlaceholder('menuid', $menuid);
$modx->setPlaceholder('menuname', $menuname);
$modx->setPlaceholder('menugenerator', $menu->get('generator'));
$modx->setPlaceholder('menudata', $menudata);
$modx->setPlaceholder('menugen', $menugen);

$modx->log(modX::LOG_LEVEL_DEBUG, '[prepareSession] exit on page '. $modx->resource->id);
return;