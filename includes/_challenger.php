<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* includes/_challenger.php ********************************/
/* Actions relatives à l'application du Challenger *********/
/* *********************************************************/
/* Dernière modification : le 16/02/15 *********************/
/* *********************************************************/


//Inclusion des fonctions spéciales pour l'appli
require DIR.'includes/_challenger_functions.php';


//Chargement des constantes définies dans la base de donnée
$constantes = $pdo->query('SELECT '.
		'flag, '.
		'value '.
	'FROM configurations')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$constantes = $constantes->fetchAll(PDO::FETCH_ASSOC);


foreach ($constantes as $constante) {
	if (!defined($constante['flag']))
		define($constante['flag'], makeValue($constante['value']));
}

if (!defined('APP_SPEED_ERROR')) 		define('APP_SPEED_ERROR', 		1500);
if (!defined('APP_URL_CHALLENGE')) 		define('APP_URL_CHALLENGE', 	'http://www.challenge-grandes-ecoles.fr');
if (!defined('APP_URL_FACEBOOK')) 		define('APP_URL_FACEBOOK', 		'https://www.facebook.com/ChallengeCentraleLyon');
if (!defined('APP_EMAIL_CHALLENGE')) 	define('APP_EMAIL_CHALLENGE', 	'asso-challenge@sympa.ec-lyon.fr');
if (!defined('APP_MAX_TRY_AUTH')) 		define('APP_MAX_TRY_AUTH', 		5);
if (!defined('APP_WAIT_AUTH')) 			define('APP_WAIT_AUTH',			60);
if (!defined('APP_SESSION_MAX_TIME')) 	define('APP_SESSION_MAX_TIME',	1800);
if (!defined('APP_SAVE_CONSTS')) 		define('APP_SAVE_CONSTS',		false);
if (!defined('APP_MESSAGE_LOGIN')) 		define('APP_MESSAGE_LOGIN',		'');
if (!defined('APP_ACTIVE_MESSAGE')) 	define('APP_ACTIVE_MESSAGE',	false);
if (!defined('APP_DATE_MALUS')) 		define('APP_DATE_MALUS',		'2015-02-01 00:00:00');
if (!defined('APP_POINTS_1ER')) 		define('APP_POINTS_1ER', 		100);
if (!defined('APP_POINTS_2E')) 			define('APP_POINTS_2E', 		70);
if (!defined('APP_POINTS_3E')) 			define('APP_POINTS_3E', 		40);


$modulesAdmin = [
	'classement' 	=> 'Classement',
	'competition' 	=> 'Compétition',
	'droits'		=> 'Droits',
	'ecoles'		=> 'Ecoles',
	'logement'		=> 'Logement',
	'statistiques' 	=> 'Statistiques',
	//'vp'			=> 'VP',
	'configurations'=> 'Configurations',
];

$typesTarifs = [
	'sportif'	 	=> 'Sportif',
	'nonsportif'	=> 'Non Sportif',
];


$labelsEtatChambre = [
	'pas_contacte'	=> 'Pas contacté',
	'contacte'	 	=> 'Contacté',
	'relance'		=> 'Relancé',
	'amies'			=> 'Héberge amies',
	'peut_etre'	 	=> 'Peut-être',
	'autorise'	 	=> 'Autorise',
	'refuse'	 	=> 'Non définitif',
];

$colorsEtatChambre = [
	'pas_contacte'	=> '#CCC',
	'contacte'	 	=> '#CCF',
	'relance'		=> '#66E',
	'amies'			=> '#FF9',
	'peut_etre'	 	=> '#FC9',
	'autorise'	 	=> '#AFA',
	'pleine'	 	=> '#6A6',
	'refuse'	 	=> '#666',
];

$labelsEtatClef = [
	'pas_recue'		=> 'Pas reçue',
	'recue'	 		=> 'Recue',
	'donnee'		=> 'Donnée',
	'recuperee'		=> 'Récupérée',
	'rendue'	 	=> 'Rendue',
];

$colorsEtatClef = [
	'pas_recue'		=> '#CCC',
	'recue'	 		=> '#FC9',
	'donnee'		=> '#FF9',
	'recuperee'		=> '#AFA',
	'rendue'	 	=> '#666',
];


//On mémorise la session en cours
$sessionNameInit = session_name();


//Actions relatives à la session pour l'administration
session_name('admin');
if (!empty($_SESSION['admin']) && (
		empty($_SESSION['admin']['last']) ||
		time() - $_SESSION['admin']['last'] >= APP_SESSION_MAX_TIME)) {
	unset($_SESSION['admin']);
	session_name('ecoles');
	$_SESSION['expire'] = true;
}


if (!empty($_SESSION['admin']['last']) &&
	!empty($_SESSION['admin']['user'])) {

	$_SESSION['admin']['last'] = time();

	
	//Récupération des droits liés à l'utilisateur sur le module admin
	$privileges = $pdo->query('SELECT '.
			'module '.
		'FROM droits_admins WHERE '.
			'id_admin = "'.(int) $_SESSION['admin']['user'].'"')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$privileges = $privileges->fetchAll(PDO::FETCH_ASSOC);

	
	$_SESSION['admin']['privileges'] = [];
	foreach ($privileges as $privilege)
		if (in_array($privilege['module'], array_keys($modulesAdmin)))
			$_SESSION['admin']['privileges'][] = $privilege['module'];
}


//Actions relatives à la session pour les écoles
session_name('ecoles');
if (!empty($_SESSION['ecole']) && (
		empty($_SESSION['ecole']['last']) ||
		time() - $_SESSION['ecole']['last'] >= APP_SESSION_MAX_TIME)) {
	unset($_SESSION['ecole']);
	session_name('ecoles');
	$_SESSION['expire'] = true;
}


if (!empty($_SESSION['ecole']['last']))
	$_SESSION['ecole']['last'] = time();


//Actions relatives à la session pour les VP
session_name('vp');
if (!empty($_SESSION['vp']) && (
		empty($_SESSION['vp']['last']) ||
		time() - $_SESSION['vp']['last'] >= APP_SESSION_MAX_TIME)) {
	unset($_SESSION['vp']);
	session_name('ecoles');
	$_SESSION['expire'] = true;
}


if (!empty($_SESSION['vp']['last']))
	$_SESSION['vp']['last'] = time();

//Remise en place de la session initiale
session_name($sessionNameInit);
