<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_sports.php *************/
/* Edition des sports **************************************/
/* *********************************************************/
/* Dernière modification : le 13/12/14 *********************/
/* *********************************************************/

//Liste des Responsables
$respos = $pdo->query('SELECT '.
		'id, '.
		'nom, '.
		'prenom '.
	'FROM admins '.
	'ORDER BY '.
		'nom ASC, '.
		'prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$respos = $respos->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$sports = $pdo->query('SELECT '.
		'DISTINCT id, '.
		'sport, '.
		'sexe, '.
		's.quota_max, '.
		's.quota_inscription, '.
		'id_respo, '.
		'multiples, '.
		'(SELECT '.
				'SUM(es.quota_max) '.
			'FROM ecoles_sports AS es WHERE '.
				'es.id_sport = s.id) AS quota_sum, '.
		'COUNT(DISTINCT e.id_ecole) AS cid, '.
		'COUNT(DISTINCT sp.id_participant) AS quota_inscrip '.
	'FROM sports AS s '.
	'LEFT JOIN equipes AS e ON '.
		'e.id_sport = s.id '.
	'LEFT JOIN sportifs AS sp ON '.
		'sp.id_sport = s.id '.
	'GROUP BY '.
		's.id '.
	'ORDER BY '.
		'sport ASC, '.
		'sexe ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sports = $sports->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


//Ajout d'un sport
if (isset($_POST['add']) &&
	!empty($_POST['sport'][0]) &&
	!empty($_POST['sexe'][0]) &&
	in_array($_POST['sexe'][0], ['m', 'f', 'h']) && 
	isset($_POST['quota'][0]) &&
	is_numeric($_POST['quota'][0]) &&
	isset($_POST['inscriptions'][0]) && (
		is_numeric($_POST['inscriptions'][0]) ||
		empty($_POST['inscriptions'][0])) &&
	!empty($_POST['respo'][0]) &&
	in_array($_POST['respo'][0], array_keys($respos))) {

	if (!isset($_POST['multiples'])) $_POST['multiples'] = array();
	$multiples = in_array('0', $_POST['multiples']);

	$pdo->exec('INSERT INTO sports SET '.
		'sport = "'.secure($_POST['sport'][0]).'", '.
		'sexe = "'.secure($_POST['sexe'][0]).'", '.
		'id_respo = '.(int) $_POST['respo'][0].', '.
		'multiples = '.($multiples ? '1' : '0').', '.
		'quota_max = '.(int) $_POST['quota'][0].', '.
		'quota_inscription = '.(empty($_POST['inscriptions'][0]) ? 'NULL' : (int) $_POST['inscriptions'][0])) 
		or die(print_r($pdo->errorInfo()));

	$add = true;
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


//On edite un sport
if (!empty($i) &&
	empty($_POST['delete']) &&
	!empty($_POST['sport'][$i]) &&
	!empty($_POST['sexe'][$i]) &&
	in_array($_POST['sexe'][$i], ['m', 'f', 'h']) && 
	isset($_POST['quota'][$i]) &&
	is_numeric($_POST['quota'][$i]) &&
	isset($_POST['inscriptions'][$i]) &&
	!empty($_POST['respo'][$i]) &&
	in_array($_POST['respo'][$i], array_keys($respos)) &&
	in_array($_POST['id'][$i], array_keys($sports)) && (
		intval($_POST['inscriptions'][$i]) &&
		$_POST['inscriptions'][$i] >= $sports[$_POST['id'][$i]]['quota_inscrip'] ||
		empty($_POST['inscriptions'][$i])) &&
	$_POST['quota'][$i] >= $sports[$_POST['id'][$i]]['quota_sum']) {

	if (!isset($_POST['multiples'])) $_POST['multiples'] = array();
	$multiples = in_array($_POST['id'][$i], $_POST['multiples']);

	$pdo->exec('UPDATE sports SET '.
			'sport = "'.secure($_POST['sport'][$i]).'", '.
			'sexe = "'.secure($_POST['sexe'][$i]).'", '.
			'id_respo = '.(int) $_POST['respo'][$i].', '.
			'multiples = '.($multiples ? '1' : '0').', '.
			'quota_max = '.(int) $_POST['quota'][$i].', '.
			'quota_inscription = '.(empty($_POST['inscriptions'][$i]) ? 'NULL' : (int) $_POST['inscriptions'][$i]).' '.
		'WHERE id = '.(int) $_POST['id'][$i]);
	
	$modify = true;
}


//On supprime un sport
else if (!empty($i) &&
	!empty($_POST['delete']) &&
	!empty($_POST['id'])) {

	$equipes = $pdo->query('SELECT '.
			'COUNT(id_ecole) AS cid '.
		'FROM equipes WHERE '.
			'id_sport = '.(int) $_POST['id'])
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$equipes = $equipes->fetchAll(PDO::FETCH_ASSOC);


	if ($delete = empty($equipes['cid']))
		$pdo->exec('DELETE FROM sports '.
			'WHERE id = '.(int) $_POST['id'][$i]);
}

if (!empty($add) ||
	!empty($modify) ||
	!empty($delete)) {

	$sports = $pdo->query('SELECT '.
			'id, '.
			'sport, '.
			'sexe, '.
			's.quota_max, '.
			's.quota_inscription, '.
			'id_respo, '.
			'multiples, '.
			'SUM(es.quota_max) AS quota_sum, '.
			'COUNT(e.id_ecole) AS cid, '.
			'COUNT(sp.id_ecole) AS quota_inscrip '.
		'FROM sports AS s '.
		'LEFT JOIN equipes AS e ON '.
			'e.id_sport = s.id '.
		'LEFT JOIN ecoles_sports AS es ON '.
			'es.id_sport = s.id '.
		'LEFT JOIN sportifs AS sp ON '.
			'sp.id_sport = s.id '.
		'GROUP BY '.
			's.id '.
		'ORDER BY '.
			'sport ASC, '.
			'sexe ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$sports = $sports->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);

}


//Inclusion du bon fichier de template
require DIR.'templates/admin/competition/sports.php';
