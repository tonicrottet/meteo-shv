<?php
/*
** doLoginAction
**
** does login action based on the $_POST[action] var
**
** uses SESSION vars: meteouserid, profileid, menuid
** in case a profile or a menu changes, unset the SESSION vars also
**
*/

// check action var
if(!isset($_POST['action'])) return;



$action = $_POST['action'];

// set debug mode
$debugmode = $modx->getOption('meteo-debugmode');

$modx->log(modX::LOG_LEVEL_DEBUG
    , '[doLoginAction] called on page '. $modx->resource->id . ' with the following properties: '
    .print_r($scriptProperties, true));


// load profiles
$package_path = $modx->getOption('core_path').'components/profiles/model/';

if(!$modx->addPackage('profiles', $package_path)) {
    return 'Konnte Package nicht laden';
}

// load meteo
$package_path = $modx->getOption('core_path').'components/meteo/model/';

if(!$modx->addPackage('meteo', $package_path)) {
    return 'Konnte Package nicht laden';
}

// get php hash from www.openwall.com/phpass
$package_path = $modx->getOption('core_path').'components/meteo/tools/';
include_once $package_path.'PasswordHash.php';


// load lexicons
$modx->lexicon->load('meteo:default');


// set form error vars
$formerror = false;

switch ($action) {
	case 'logout':
		//reset session vars
		unset($_SESSION['meteouserid']);

		//reset and delete cookie
		if($_COOKIE['meteouser_token']) {
			$token = $_COOKIE['meteouser_token'];

			unset($_COOKIE['meteouser_token']);
			setcookie('meteouser_token', '', time() - 3600);
			unset($_COOKIE['meteouser_authenticator']);
			setcookie('meteouser_authenticator', '', time() - 3600);

			$shvmeteousertoken = $modx->getObject('SHVMeteoUserToken', array('token' => $token));
			if($shvmeteousertoken != null) $shvmeteousertoken->remove();
		}

		// and go home
		$url = $modx->makeUrl($modx->getOption('site_start'));
		$modx->sendRedirect($url);
		break;

	case 'login': 
		// check password

		// set cookie if "remember me" is set
		// serial id and token
		// store in DB

		// check if user has a meteouser profile
		// user doesn't have a meteouser profile
		// make a meteouser for him

		if($_POST['shvnr']) {
			$reqshvnr = $_POST['shvnr'];
		} else {
			$formerror = true;
			$modx->setPlaceholder('dLA.formerror.shvnr', $modx->lexicon('login.erremptyfield'));
			$modx->setPlaceholder('dLA.formerror.message', $modx->lexicon('login.errformnotcomplete'));
		}

		if($_POST['password']) {
			$reqpassword = $_POST['password'];
		} else {
			$formerror = true;
			$modx->setPlaceholder('dLA.formerror.password', $modx->lexicon('login.erremptyfield'));
			$modx->setPlaceholder('dLA.formerror.message', $modx->lexicon('login.errformnotcomplete'));
			$modx->setPlaceholder('dLA.value.shvnr', $reqshvnr);
		}

		// exit here
		if($formerror) return;

		// from here we have values inside $reqshvnr and $reqpassword

		// try to find the with the nr field first, this should be unique
		$shvuser = $modx->getObject('SHVUser', array('nr' => $reqshvnr));

		if($shvuser == null || $shvuser->get('id') == 1) {
			// try with id field, but this is by "DESIGN" unfortunately not unique
			// so, get a Collection

			// OLDCODE 
			// $shvuser = $modx->getObject('SHVUser', $reqshvnr);
			// /OLDCODE

			// NEWCODE
			$shvusers = $modx->getIterator('SHVUser', array('id' => $reqshvnr));

			$t_hasher = new PasswordHash(8, FALSE);
			foreach ($shvusers as $curuser) {
				$hash = $curuser->get('password');
				if($t_hasher->CheckPassword($reqpassword, $hash)) {
					// FOUND!!
					$shvuser = $curuser;
				}
			}

			// /NEWCODE
		}

		if($shvuser == null || $shvuser->get('id') == 1) {

			// Check SHVExtUser 
			$shvextuser = $modx->getObject('SHVExtUser', array('email' => $reqshvnr));

			if($shvextuser == null || $shvextuser->get('id') == 1) {
				$formerror = true;
				$modx->setPlaceholder('dLA.formerror.message', $modx->lexicon('login.loginerror'));
				$modx->setPlaceholder('dLA.value.shvnr', $reqshvnr);
				return;
			}

			if($reqpassword != $shvextuser->get('password')) {
				$formerror = true;
				$modx->setPlaceholder('dLA.formerror.message', $modx->lexicon('login.loginerror'));
				$modx->setPlaceholder('dLA.value.shvnr', $reqshvnr);
				return;
			}

			$userid = $shvextuser->get('id');
			$username = $shvextuser->get('name');

		} else {
			// a $shvuser is found
			//check password against
			$passhashstring = $shvuser->get('password');

			$t_hasher = new PasswordHash(8, FALSE);
			$check = $t_hasher->CheckPassword($reqpassword, $passhashstring);

			if(!$check) {
				$formerror = true;
				$modx->setPlaceholder('dLA.formerror.message', $modx->lexicon('login.loginerror'));
				$modx->setPlaceholder('dLA.value.shvnr', $reqshvnr);
				return;
			}

			// Code for change 17.04.2015, id is not unique, nr is instead, needs a system reset before change
			// $userid = $shvuser->get('nr');
			$userid = $shvuser->get('id');
			$username = $shvuser->get('nr');
		}



		// arrived here we are password and user are validated
		// username and userid vars are set

		// get a meteouserid and set into session var


		$shvmeteouser = $modx->getObject('SHVMeteoUser', array('SHVUser' => $userid));

		if($shvmeteouser == null) {
			// no meteouser link, build it

			// make a meteouser first
			$meteouser = $modx->newObject('MeteoUser');
			$meteouser->set('shvnr', $userid);
			$meteouser->set('name', $username);

			$meteouser->save();

			// link it
			$shvmeteouser = $modx->newObject('SHVMeteoUser');

			$shvmeteouser->set('SHVUser', $userid);
			$shvmeteouser->set('MeteoUser', $meteouser->get('id'));
			$shvmeteouser->save();
		}

		// check for permanently logged in flag
		if($_POST['permalogin']) {
			// create an entry in SHVMeteoUserToken and set the cookies

			$shvmeteousertoken = $modx->getObject('SHVMeteoUserToken', array('SHVMeteoUser' => $shvmeteouser->get('id')));

			if($shvmeteousertoken != null) {
				$shvmeteousertoken->remove();
			}

			$token = base64_encode($t_hasher->get_random_bytes(32));
			$auth =  base64_encode($t_hasher->get_random_bytes(32));

			$expires = new DateTime('now');
    		$expires->add(new DateInterval('P14D'));

			$shvmeteousertoken = $modx->newObject('SHVMeteoUserToken');

			$shvmeteousertoken->set('token', $token);
			$shvmeteousertoken->set('verifier', $t_hasher->HashPassword($auth));
			$shvmeteousertoken->set('SHVMeteoUser', $shvmeteouser->get('id'));
			$shvmeteousertoken->set('expires', $expires->format('Y-m-d H:i:s'));

			$shvmeteousertoken->save();

			setcookie(
				'meteouser_token',
				$token,
				time() + 1209600, 
				'/');

			setcookie(
				'meteouser_authenticator',
				$auth,
				time() + 1209600, 
				'/');

		}

		// validate SESSION
		$_SESSION['meteouserid'] = $shvmeteouser->get('MeteoUser');

		// return to some url
		if($_SESSION['frompageid']) {
			$url = $modx->makeUrl($_SESSION['frompageid']);
			$modx->sendRedirect($url);
			return;
		}

		// or go home
		$url = $modx->makeUrl($modx->getOption('site_start'));
		$modx->sendRedirect($url);
		return;

		break;	
}