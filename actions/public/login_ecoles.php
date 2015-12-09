<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/public/login_ecoles.php *************************/
/* Gére la connexion pour les écoles ***********************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


if (!empty($_SESSION['ecole']))
	die(header('location:'.url('ecole/accueil', false, false)));


if (!empty($_POST['login_ecole']) &&
	!empty($_POST['login']) &&
	!empty($_POST['pass'])) {

	if (empty($_SESSION['tentatives']) ||
		time() - $_SESSION['tentatives']['start'] > APP_WAIT_AUTH)
		$_SESSION['tentatives'] = [
			'start' => time(),
			'count' => 0];


	$hash = hashPass($_POST['pass']);
	$user = $pdo->query('SELECT '.
			'id, '.
			'connexion, '.
			'etat_inscription '.
		'FROM ecoles WHERE '.
			'login = "'.secure($_POST['login']).'" AND '.
			'pass = "'.$hash.'"') or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$user = $user->fetch(PDO::FETCH_ASSOC) ;


	if (!empty($user) && 
		$user['etat_inscription'] != 'fermee' &&
		$_SESSION['tentatives']['count'] < APP_MAX_TRY_AUTH) {

		$pdo->exec('UPDATE ecoles SET '.
				'connexion = NOW() '.
			'WHERE '.
				'id = '.$user['id']);

	
		$_SESSION['ecole'] = [
			'start' => time(),
			'last' => time(),
			'user' => $user['id'],
			'first' => empty($user['connexion']),
		];


		if (empty($user['connexion']))
			die(header('location:'.url('ecole/accueil', false, false)));

		else
			die(header('location:'.url('ecole/participants', false, false)));

	}

	else if (!empty($user) && 
		$user['etat_inscription'] == 'fermee')
		$fermee = true;

	else {
		$error = true;
		$_SESSION['tentatives']['count']++;
	}
}


//Inclusion du bon fichier de template
require DIR.'templates/public/login_ecoles.php';