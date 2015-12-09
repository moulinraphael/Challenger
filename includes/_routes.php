<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* includes/_routes.php ************************************/
/* Déclaration de toutes les routes de l'application *******/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


define('GET', '/?(?:\?.*)?');

$routes = !defined('SITE_ENABLED') || !SITE_ENABLED ? array(
	'(?:.*)' 									=> 'public/disabled.php',
) : array(
	'(?:accueil)?'						=> 'public/login.php',
	'ecole'								=> 'public/login_ecoles.php',
	'admin'								=> 'public/login_admin.php',
	'cas'								=> 'public/login_admin.php',
	'vp'								=> 'public/login_vp.php',
	'logout'							=> 'public/logout.php',
	'contact'							=> 'public/contact.php',
	'classement'						=> 'public/classement.php',

	
	'ecole/accueil'						=> 'ecoles/accueil.php',
	'ecole/logout'						=> 'ecoles/logout.php',
	'ecole/participants'				=> 'ecoles/participants.php',
	'ecole/tarifs'						=> 'ecoles/tarifs.php',
	'ecole/equipes'						=> 'ecoles/equipes.php',
	'ecole/sportifs'					=> 'ecoles/sportifs.php',
	'ecole/recapitulatif'				=> 'ecoles/recapitulatif.php',


	'vp/accueil'						=> 'vp/accueil.php',
	'vp/logout'							=> 'vp/logout.php',

	

	'admin/accueil'						=> 'admin/accueil.php',
	'admin/compte'						=> 'admin/compte.php',
	'admin/module/(\w+)(?:/(\w+))?'		=> 'admin/module.php',
	'admin/logout'						=> 'admin/logout.php',


	

);


foreach ($routes as $route => $action) {
	$routes[$route.GET] = $action;
	unset($routes[$route]);
}
