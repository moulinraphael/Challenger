<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/public/login_vp.php *****************************/
/* Gére la connexion pour les VP aides/sites ***************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


if (!empty($_SESSION['vp']))
	die(header('location:'.url('vp/accueil', false, false)));


if (!empty($_POST['login_vp']) &&
	!empty($_POST['login']) &&
	!empty($_POST['pass'])) {

	if (empty($_SESSION['tentatives']) ||
		time() - $_SESSION['tentatives']['start'] > APP_WAIT_AUTH)
		$_SESSION['tentatives'] = [
			'start' => time(),
			'count' => 0];


	$hash = hashPass($_POST['pass']);
	$user = $pdo->query('SELECT '.
			'id '.
		'FROM vp WHERE '.
			'login = "'.secure($_POST['login']).'" AND '.
			'pass = "'.$hash.'"') or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$user = $user->fetch(PDO::FETCH_ASSOC) ;


	if (!empty($user) && 
		$_SESSION['tentatives']['count'] < APP_MAX_TRY_AUTH) {
		$_SESSION['vp'] = [
			'start' => time(),
			'last' => time(),
			'user' => $user['id'],
		];
		die(header('location:'.url('vp/accueil', false, false)));
	}

	else {
		$error = true;
		$_SESSION['tentatives']['count']++;
	}
}


//Inclusion du bon fichier de template
require DIR.'templates/public/login_vp.php';