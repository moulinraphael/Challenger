<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/classement/action_ediiton.php *************/
/* Edition des classements *********************************/
/* *********************************************************/
/* Dernière modification : le 16/02/15 *********************/
/* *********************************************************/


$ecoles = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom '.
	'FROM ecoles AS e '.
	'ORDER BY e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$ecoles = $ecoles->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$sports = $pdo->query('SELECT '.
		's.id, '.
		'(SELECT COUNT(eq.id_ecole) FROM equipes AS eq WHERE eq.id_sport = s.id) AS nb_equipes, '.
		'(SELECT COUNT(sp.id_participant) FROM sportifs AS sp WHERE sp.id_sport = s.id) AS nb_sportifs, '.
		's.sport, '.
		's.sexe '.
	'FROM sports AS s '.
	'WHERE ID NOT IN (SELECT id_sport FROM classements) '.
	'ORDER BY '.
		'sport ASC, '.
		'sexe ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sports = $sports->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$classements = $pdo->query('SELECT '.
		'c.*, '.
		's.sport, '.
		's.sexe '.
	'FROM sports AS s '.
	'JOIN classements AS c ON '.
		's.id = c.id_sport '.
	'ORDER BY '.
		's.sport ASC, '.
		's.sexe ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$classements = $classements->fetchAll(PDO::FETCH_ASSOC);


if (!isset($_POST['ex12'])) $_POST['ex12'] = array();
if (!isset($_POST['ex23'])) $_POST['ex23'] = array();
if (!isset($_POST['ex3'])) $_POST['ex3'] = array();


//Ajout d'un participant
if (isset($_POST['add']) &&
	!empty($_POST['sport'][0]) &&
	isset($_POST['coeff'][0]) &&
	!empty($_POST['ecole1'][0]) &&
	in_array($_POST['ecole1'][0], array_keys($ecoles)) &&
	!empty($_POST['ecole2'][0]) &&
	in_array($_POST['ecole2'][0], array_keys($ecoles)) &&
	!empty($_POST['ecole3'][0]) &&
	in_array($_POST['ecole3'][0], array_keys($ecoles)) && 
	$_POST['ecole1'][0] != $_POST['ecole2'][0] &&
	$_POST['ecole1'][0] != $_POST['ecole3'][0] &&
	$_POST['ecole2'][0] != $_POST['ecole3'][0] && (
		in_array('0', $_POST['ex3']) &&
		!empty($_POST['ecole3ex'][0]) &&
		in_array($_POST['ecole3ex'][0], array_keys($ecoles)) &&
		$_POST['ecole1'][0] != $_POST['ecole3ex'][0] &&
		$_POST['ecole2'][0] != $_POST['ecole3ex'][0] &&
		$_POST['ecole3'][0] != $_POST['ecole3ex'][0] ||
		!in_array('0', $_POST['ex3']))) {

	$pdo->exec('INSERT INTO classements SET '.
		'id_sport = '.(int) secure($_POST['sport'][0]).', '.
		'coeff = '.(int) secure($_POST['coeff'][0]).', '.
		'id_ecole_1 = '.(int) secure($_POST['ecole1'][0]).', '.
		'id_ecole_2 = '.(int) secure($_POST['ecole2'][0]).', '.
		'id_ecole_3 = '.(int) secure($_POST['ecole3'][0]).', '.
		'ex_12 = '.(in_array('0', $_POST['ex12']) ? 1 : 0).', '.
		'ex_23 = '.(in_array('0', $_POST['ex23']) ? 1 : 0).', '.
		'ex_3 = '.(in_array('0', $_POST['ex3']) ? 1 : 0).', '.
		'id_ecole_3ex = '.(int) secure(in_array('0', $_POST['ex3']) ? $_POST['ecole3ex'][0] : ''));

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


//On edite un participant
if (isset($i) &&
	empty($_POST['delete']) &&
	isset($_POST['coeff'][$i]) &&
	!empty($_POST['ecole1'][$i]) &&
	in_array($_POST['ecole1'][$i], array_keys($ecoles)) &&
	!empty($_POST['ecole2'][$i]) &&
	in_array($_POST['ecole2'][$i], array_keys($ecoles)) &&
	!empty($_POST['ecole3'][$i]) &&
	in_array($_POST['ecole3'][$i], array_keys($ecoles)) && 
	$_POST['ecole1'][$i] != $_POST['ecole2'][$i] &&
	$_POST['ecole1'][$i] != $_POST['ecole3'][$i] &&
	$_POST['ecole2'][$i] != $_POST['ecole3'][$i] && (
		in_array($_POST['id'][$i], $_POST['ex3']) &&
		!empty($_POST['ecole3ex'][$i]) &&
		in_array($_POST['ecole3ex'][$i], array_keys($ecoles)) &&
		$_POST['ecole1'][$i] != $_POST['ecole3ex'][$i] &&
		$_POST['ecole2'][$i] != $_POST['ecole3ex'][$i] &&
		$_POST['ecole3'][$i] != $_POST['ecole3ex'][$i] ||
		!in_array($_POST['id'][$i], $_POST['ex3']))) {

	$pdo->exec('UPDATE classements SET '.
			'coeff = '.(int) secure($_POST['coeff'][$i]).', '.
			'id_ecole_1 = '.(int) secure($_POST['ecole1'][$i]).', '.
			'id_ecole_2 = '.(int) secure($_POST['ecole2'][$i]).', '.
			'id_ecole_3 = '.(int) secure($_POST['ecole3'][$i]).', '.
			'ex_12 = '.(in_array($_POST['id'][$i], $_POST['ex12']) ? 1 : 0).', '.
			'ex_23 = '.(in_array($_POST['id'][$i], $_POST['ex23']) ? 1 : 0).', '.
			'ex_3 = '.(in_array($_POST['id'][$i], $_POST['ex3']) ? 1 : 0).', '.
			'id_ecole_3ex = '.(int) secure(in_array($_POST['id'][$i], $_POST['ex3']) ? $_POST['ecole3ex'][$i] : '').' '.
		'WHERE id_sport = '.$_POST['id'][$i]);


	$modify = true;

}


//On supprime un participant
else if (isset($i) &&
	!empty($_POST['delete'])) {

	$pdo->exec('DELETE FROM classements '.
		'WHERE '.
			'id_sport = '.abs((int) $_POST['id'][$i]))
		or die(print_r($pdo->errorInfo()));

	$delete = true;
}


if (!empty($add) ||
	!empty($modify) ||
	!empty($delete)) {

	$classements = $pdo->query('SELECT '.
			'c.*, '.
			's.sport, '.
			's.sexe '.
		'FROM classements AS c '.
		'JOIN sports AS s ON '.
			's.id = c.id_sport '.
		'ORDER BY '.
			's.sport ASC, '.
			's.sexe ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$classements = $classements->fetchAll(PDO::FETCH_ASSOC);


	$sports = $pdo->query('SELECT '.
			's.id, '.
			'(SELECT COUNT(es.id_ecole) FROM ecoles_sports AS es WHERE es.id_sport = s.id) AS nb_equipes, '.
			'(SELECT COUNT(sp.id_participant) FROM sportifs AS sp WHERE sp.id_sport = s.id) AS nb_sportifs, '.
			's.sport, '.
			's.sexe '.
		'FROM sports AS s '.
		'WHERE ID NOT IN (SELECT id_sport FROM classements) '.
		'ORDER BY '.
			'sport ASC, '.
			'sexe ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$sports = $sports->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);

}


//Inclusion du bon fichier de template
require DIR.'templates/admin/classement/edition.php';
