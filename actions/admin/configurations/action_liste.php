<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/configurations/action_liste.php ***********/
/* Edition des constantes **********************************/
/* *********************************************************/
/* Dernière modification : le 21/11/14 *********************/
/* *********************************************************/

function makeFlag($str) {
	return preg_replace('/[^\p{L}\p{N}_]+/i', '', $str);
}

//Ajout d'une constante
if (defined('APP_SAVE_CONSTS') &&
	APP_SAVE_CONSTS &&
	isset($_POST['add']) &&
	!empty(makeFlag($_POST['flag'][0])) &&
	!empty($_POST['nom'][0]) &&
	!empty($_POST['value'][0])) {

	$count = $pdo->query('SELECT '.
		'COUNT(flag) AS cflag '.
		'FROM configurations '.
		'WHERE '.
			'flag = "'.makeFlag($_POST['flag'][0]).'"')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$count = $count->fetch(PDO::FETCH_ASSOC);


	if (empty($count['cflag']))
		$pdo->exec($s = 'INSERT INTO configurations SET '.
			'flag = "'.makeFlag($_POST['flag'][0]).'", '.
			'nom = "'.secure($_POST['nom'][0]).'", '.
			'value = "'.secure($_POST['value'][0]).'"');

	$add = empty($count['cflag']);
}


//On récupère l'indice du champ concerné
if ((!empty($_POST['delete']) || 
	!empty($_POST['edit'])) &&
	isset($_POST['last_flag']) &&
	is_array($_POST['last_flag']))
	$i = array_search(empty($_POST['delete']) ?
		$_POST['edit'] :
		$_POST['delete'],
		$_POST['last_flag']);


//On edite une constante
if (!empty($i) &&
	empty($_POST['delete']) &&
	!empty($_POST['nom'][$i]) &&
	!empty(makeFlag($_POST['flag'][$i])) &&
	!empty($_POST['value'][$i]) &&
	!empty($_POST['last_flag'][$i])) {

	$count = $pdo->query('SELECT '.
		'COUNT(flag) AS cflag '.
		'FROM configurations '.
		'WHERE '.
			'flag = "'.makeFlag($_POST['flag'][$i]).'" AND '.
			'flag <> "'.makeFlag($_POST['flag'][$i]).'"')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$count = $count->fetch(PDO::FETCH_ASSOC);


	if (empty($count['cflag']))
	$pdo->exec('UPDATE configurations SET '.
		'nom = "'.secure($_POST['nom'][$i]).'", '.
		'flag = "'.makeFlag($_POST['flag'][$i]).'", '.
		'value = "'.secure($_POST['value'][$i]).'" '.
		'WHERE flag = "'.makeFlag($_POST['last_flag'][$i]).'"');
	
	$modify = empty($count['cflag']);
}


//On supprime une constante
else if (defined('APP_SAVE_CONSTS') &&
	APP_SAVE_CONSTS &&
	!empty($i) &&
	!empty($_POST['delete']) &&
	!empty($_POST['last_flag'])) {
	$pdo->exec('DELETE FROM configurations '.
		'WHERE flag = "'.makeFlag($_POST['last_flag'][$i]).'"');
	$delete = true;
}


$constantes = $pdo->query('SELECT '.
		'flag, '.
		'nom, '.
		'value '.
	'FROM configurations '.
	'ORDER BY '.
		'flag ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$constantes = $constantes->fetchAll(PDO::FETCH_ASSOC);


//Inclusion du bon fichier de template
require DIR.'templates/admin/configurations/liste.php';
