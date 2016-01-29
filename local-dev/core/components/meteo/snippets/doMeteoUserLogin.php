<?php
/*
** doMeteoUserLogin
**
** login delegate for Meteo User, acts as a interface between profiles and meteo
** modules
**
** meteo component takes care of the login user data
** profiles component only knows about meteousers
**
** so the meteo component is responsible of authentification of the users
**
** returns the meteouserid
** OR nothing if an error occurred
** OR performs the login (asks user for login), which is actually done by
** redirecting to the login page and the login page should do the login and
** return to the requested url
**
** in case of redirect, it sets the SESSION vars 'frompageid' and 'fromurl'.
**
*/

$modx->log(modX::LOG_LEVEL_DEBUG
    , '[doMeteoUserLogin] called on page '. $modx->resource->id . ' with the following properties: '
    .print_r($scriptProperties, true));

// set debug mode
$debugmode = $modx->getOption('meteo-debugmode');

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

if($debugmode) {
	// Load Debug user
	$meteouser = $modx->getObject('MeteoUser', array('shvnr' => $debuguserSHVNR));
	$meteouserid = $meteouser->get('id');
	return $meteouserid;
}

$meteouserid = 0;

//User not set, otherwise this delegate didnt get called
// check cookie or set debug user or goto login
if ($_COOKIE['meteouser_token']) {

	$token = $_COOKIE['meteouser_token'];
	$shvmeteousertoken = $modx->getObject('SHVMeteoUserToken', array('token' => $token));

	if($shvmeteousertoken == null) {
		setcookie('meteouser_token', '', time() - 3600);
		unset($_COOKIE['meteouser_token']);
		setcookie('meteouser_authenticator', '', time() - 3600);
		unset($_COOKIE['meteouser_authenticator']);

		//goto login
		$_SESSION['frompageid'] = $modx->resource->get('id');
		$_SESSION['fromurl'] = $_SERVER['REQUEST_URI'];

		$url = $modx->makeUrl($modx->getOption('loginpage'));
		$modx->sendRedirect($url);
		return;
		
	} else {
		// got here, means we have a cookie with a valid token and no session yet.
		// perform autologin

		$auth = $_COOKIE['meteouser_authenticator'];

		$t_hasher = new PasswordHash(8, FALSE);
		if($t_hasher->CheckPassword($auth, $shvmeteousertoken->get('verifier'))) {
			//successful autologin
			// get meteouserid
			$shvmeteouser = $modx->getObject('SHVMeteoUser', $shvmeteousertoken->get('SHVMeteoUser'));

			$meteouserid = $shvmeteouser->get('MeteoUser');
			$_SESSION['meteouserid'] = $meteouserid;

			$shvmeteousertoken->remove();
			
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

		} else {
			$shvmeteousertoken->remove();

			//goto login
			$_SESSION['frompageid'] = $modx->resource->get('id');
			$_SESSION['fromurl'] = $_SERVER['REQUEST_URI'];

			$url = $modx->makeUrl($modx->getOption('loginpage'));
			$modx->sendRedirect($url);
			return;
		}
	}

} else {
	//goto login
	$_SESSION['frompageid'] = $modx->resource->get('id');
	$_SESSION['fromurl'] = $_SERVER['REQUEST_URI'];

	$url = $modx->makeUrl($modx->getOption('loginpage'));
	$modx->sendRedirect($url);
	return;
}

return $meteouserid;