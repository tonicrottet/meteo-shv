<?php
/*
** checkLogin
**
** checks if logged in
** returns loggedintpl if logged in or notloggedintpl if not
** setting message placeholder
**
** uses SESSION vars: meteouserid, profileid, menuid
** in case a profile or a menu changes, unset the SESSION vars also
**
*/

// set debug mode
$debugmode = $modx->getOption('meteo-debugmode');

$modx->log(modX::LOG_LEVEL_DEBUG
    , '[checkLogin] called on page '. $modx->resource->id . ' with the following properties: '
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


$loggedintpl = !empty($loggedintpl) ? $loggedintpl : '';
$notloggedintpl = !empty($notloggedintpl) ? $notloggedintpl : '';

$outputtpl = $notloggedintpl;
$meteouserid = '';

// check session var
if (isset($_SESSION['meteouserid']))
{
	$meteouserid = $_SESSION['meteouserid'];
	$outputtpl = $loggedintpl;
} else 

{
	if ($_COOKIE['meteouser_token']) {
		$token = $_COOKIE['meteouser_token'];
		$shvmeteousertoken = $modx->getObject('SHVMeteoUserToken', array('token' => $token));

		if($shvmeteousertoken == null) {
			setcookie('meteouser_token', '', time() - 3600);
			unset($_COOKIE['meteouser_token']);
			setcookie('meteouser_authenticator', '', time() - 3600);
			unset($_COOKIE['meteouser_authenticator']);
		} else {
			// got here, means we have a cookie with a valid token and no session yet.
			// perform autologin

			$auth = $_COOKIE['meteouser_authenticator'];

			$t_hasher = new PasswordHash(8, FALSE);
			if($t_hasher->CheckPassword($auth, $shvmeteousertoken->get('verifier'))) {
				//successful autologin
				// get meteouserid
				$shvmeteouser = $modx->getObject('SHVMeteoUser', $shvmeteousertoken->get('SHVMeteoUser'));

				$_SESSION['meteouserid'] = $shvmeteouser->get('MeteoUser');
				$meteouserid = $_SESSION['meteouserid'];

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

				$outputtpl = $loggedintpl;

			} else {
				$shvmeteousertoken->remove();
			}
		}
	}
}

if(!empty($meteouserid)) {
	$meteouser = $modx->getObject('MeteoUser', $meteouserid);
	$shvno = $meteouser->get('name');
}

return $modx->getChunk($outputtpl, array('shvnr' => $shvno));
