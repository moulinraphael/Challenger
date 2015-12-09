<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/droits/action_admins.php ******************/
/* Edition des admins **************************************/
/* *********************************************************/
/* Dernière modification : le 24/11/14 *********************/
/* *********************************************************/


//Ajout d'un admin
if (isset($_POST['add']) &&
	!empty($_POST['nom'][0]) &&
	!empty($_POST['prenom'][0]) &&
	!empty($_POST['email'][0]) &&
	!empty($_POST['telephone'][0]) &&
	!empty($_POST['login'][0]) &&
	isset($_POST['poste'][0]) &&
	!empty($_POST['pass'][0])) {

	if (!isset($_POST['contact'])) $_POST['contact'] = array();
	$contact = in_array('0', $_POST['contact']);

	$count = $pdo->query('SELECT '.
		'COUNT(id) AS cid '.
		'FROM admins '.
		'WHERE '.
			'login = "'.secure($_POST['login'][0]).'"')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$count = $count->fetch(PDO::FETCH_ASSOC);


	if (empty($count['cid']))
		$pdo->exec($s = 'INSERT INTO admins SET '.
			'auth_type = "db", '.
			'nom = "'.secure($_POST['nom'][0]).'", '.
			'prenom = "'.secure($_POST['prenom'][0]).'", '.
			'email = "'.secure($_POST['email'][0]).'", '.
			'telephone = "'.secure($_POST['telephone'][0]).'", '.
			'login = "'.secure($_POST['login'][0]).'", '.
			'poste = "'.secure($_POST['poste'][0]).'", '.
			'contact = '.($contact ? '1' : '0').', '.
			'pass = "'.hashPass($_POST['pass'][0]).'"');

	$add = empty($count['cid']);
}


//On récupère l'indice du champ concerné
if ((!empty($_POST['delete']) || 
	!empty($_POST['edit'])) &&
	isset($_POST['id']) &&
	is_array($_POST['id']))
	$i = array_search(empty($_POST['delete']) ?
		$_POST['edit'] :
		$_POST['delete'],
		$_POST['id']);


//On edite un admin
if (!empty($i) &&
	empty($_POST['delete']) &&
	!empty($_POST['nom'][$i]) &&
	!empty($_POST['prenom'][$i]) &&
	!empty($_POST['login'][$i]) &&
	isset($_POST['login'][$i]) &&
	!empty($_POST['email'][$i]) &&
	!empty($_POST['telephone'][$i]) &&
	isset($_POST['pass'][$i]) &&
	!empty($_POST['id'][$i]) &&
	intval($_POST['id'][$i])) {

	if (!isset($_POST['contact'])) $_POST['contact'] = array();
	$contact = in_array($_POST['id'][$i], $_POST['contact']);

	$count = $pdo->query('SELECT '.
		'COUNT(id) AS cid '.
		'FROM admins '.
		'WHERE '.
			'auth_type = "db" AND '.
			'login = "'.secure($_POST['login'][$i]).'" AND '.
			'id <> '.(int) $_POST['id'][$i])
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$count = $count->fetch(PDO::FETCH_ASSOC);


	if (empty($count['cid']))
		$pdo->exec('UPDATE admins SET '.
				'nom = "'.secure($_POST['nom'][$i]).'", '.
				'prenom = "'.secure($_POST['prenom'][$i]).'", '.
				'email = "'.secure($_POST['email'][$i]).'", '.
				'telephone = "'.secure($_POST['telephone'][$i]).'", '.
				'login = "'.secure($_POST['login'][$i]).'", '.
				'poste = "'.secure($_POST['poste'][$i]).'", '.
				(!empty($_POST['pass'][$i]) ? 'pass = "'.hashPass($_POST['pass'][$i]).'", ' : '').
				'contact = '.($contact ? '1' : '0').' '.
			'WHERE '.
				'auth_type = "db" AND '.
				'id = '.(int) $_POST['id'][$i]);
		
	$modify = empty($count['cid']);
}


//On supprime un admin
else if (!empty($i) &&
	!empty($_POST['delete']) &&
	!empty($_POST['id'][$i]) &&
	intval($_POST['id'][$i])) {

	$pdo->exec('DELETE FROM admins '.
		'WHERE id = '.(int) $_POST['id'][$i]);

	$delete = true;
}


$admins = $pdo->query('SELECT '.
		'id, '.
		'nom, '.
		'prenom, '.
		'email, '.
		'telephone, '.
		'login, '.
		'contact, '.
		'poste '.
	'FROM admins '.
	'WHERE '.
		'auth_type = "db" '.
	'ORDER BY '.
		'nom ASC, '.
		'prenom ASC, '.
		'login ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$admins = $admins->fetchAll(PDO::FETCH_ASSOC);


//Inclusion du bon fichier de template
require DIR.'templates/admin/droits/admins.php';
