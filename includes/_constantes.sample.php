<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* includes/_constantes.php ********************************/
/* Toutes les constantes du site ***************************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//Constantes base de données
//Constantes pour les essais en local
if (!defined('LOCAL'))
	define('LOCAL',				$_SERVER['SERVER_NAME'] == 'localhost');

if (LOCAL) {
	define('DB_HOST', 			'127.0.0.1');
	define('DB_NAME', 			'db');
	define('DB_USER', 			'root');
	define('DB_PASS', 			'');
}

//On est sur le serveur de production
else {
	define('DB_HOST', 			'');
	define('DB_NAME', 			'');
	define('DB_USER', 			'');
	define('DB_PASS', 			'');
}

//Configuration pour le CAS
define('CONFIG_CAS_HOST', 		'cas.ec-lyon.fr');
define('CONFIG_CAS_PORT',		443);
define('CONFIG_CAS_CONTEXT', 	'');

//Debug
define('DEBUG_ACTIVE_LOCAL',	true);
define('DEBUG_ACTIVE_ONLINE',	true);
define('DEBUG_ACTIVE',			!LOCAL && DEBUG_ACTIVE_ONLINE || LOCAL && DEBUG_ACTIVE_LOCAL);


//Configuration d'application
define('URL_API_ECLAIR', 		'http://api.eclair.ec-lyon.fr');
define('SITE_ENABLED_LOCAL',	true);
define('SITE_ENABLED_ONLINE',	true);
define('SITE_ENABLED',			!LOCAL && SITE_ENABLED_ONLINE || LOCAL && SITE_ENABLED_LOCAL);
define('APP_SEED',				'');

