<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/ecoles/action_accueil.php *****************/
/* Accueil du module des Ecoles, gestion du message ********/
/* *********************************************************/
/* Dernière modification : le 21/11/14 *********************/
/* *********************************************************/


if (!empty($_POST['edit']) &&
	isset($_POST['active']) &&
	isset($_POST['message']) &&
	in_array($_POST['active'], array('1', '0'))) {

	$pdo->exec('DELETE FROM configurations '.
		'WHERE '.
			'flag = "APP_ACTIVE_MESSAGE" OR '.
			'flag = "APP_MESSAGE_LOGIN"');

	
	$pdo->exec('INSERT INTO configurations SET '.
		'flag = "APP_ACTIVE_MESSAGE", '.
		'value = "'.($_POST['active'] ? 'true' : 'false').'", '.
		'nom = "Activation du message affiché sur la page de login"');


	$pdo->exec('INSERT INTO configurations SET '.
		'flag = "APP_MESSAGE_LOGIN", '.
		'value = "'.secure($_POST['message']).'", '.
		'nom = "Message affiché sur la page de login des écoles"');

	$edit = true;
} 


$_POST['active'] = empty($_POST['active']) ? 
	(empty(APP_ACTIVE_MESSAGE) ? false : APP_ACTIVE_MESSAGE) : $_POST['active'];

$_POST['message'] = empty($_POST['message']) ? 
	(empty(APP_MESSAGE_LOGIN) ? null : APP_MESSAGE_LOGIN) : $_POST['message'];


//Inclusion du bon fichier de template
require DIR.'templates/admin/ecoles/accueil.php';
