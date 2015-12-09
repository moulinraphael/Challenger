<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/ecoles/equipes.php ******************************/
/* Edition des équipes *************************************/
/* *********************************************************/
/* Dernière modification : le 13/12/14 *********************/
/* *********************************************************/


if (empty($_SESSION['ecole']) ||
	empty($_SESSION['ecole']['user']))
	die(header('location:'.url('accueil', false, false)));

$multiples = $pdo->query('SELECT '.
		'id '.
	'FROM sports WHERE '.
		'multiples = 1')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$multiples = $multiples->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
$multiples = implode(',', array_keys($multiples));


$ecole = $pdo->query('SELECT '.
		'e.*, '.
		'(SELECT COUNT(p1.id) FROM participants AS p1 WHERE p1.id_ecole = e.id) AS quota_inscriptions, '.
		'(SELECT COUNT(p2.id) FROM participants AS p2 WHERE p2.id_ecole = e.id AND p2.sportif = 1) AS quota_sportif_view, '.
		'(SELECT COUNT(p3.id) FROM participants AS p3 WHERE p3.id_ecole = e.id AND p3.pompom = 1) AS quota_pompom_view, '.
		'(SELECT COUNT(p4.id) FROM participants AS p4 WHERE p4.id_ecole = e.id AND p4.fanfaron = 1) AS quota_fanfaron_view, '.
		'(SELECT COUNT(p6.id) FROM participants AS p6 WHERE p6.id_ecole = e.id AND p6.pompom = 1 AND p6.sportif = 0) AS quota_pompom_nonsportif_view, '.
		'(SELECT COUNT(p7.id) FROM participants AS p7 WHERE p7.id_ecole = e.id AND p7.fanfaron = 1 AND p7.sportif = 0) AS quota_fanfaron_nonsportif_view, '.
		'(SELECT COUNT(p8.id) FROM participants AS p8 JOIN tarifs AS t8 ON t8.id = p8.id_tarif AND t8.logement = 1 WHERE p8.id_ecole = e.id AND p8.sexe = "f") AS quota_filles_logees_view, '.
		'(SELECT COUNT(p9.id) FROM participants AS p9 JOIN tarifs AS t9 ON t9.id = p9.id_tarif AND t9.logement = 1 WHERE p9.id_ecole = e.id AND p9.sexe = "h") AS quota_garcons_loges_view '.
	'FROM ecoles AS e '.
	'WHERE '.
		'e.id = '.(int) $_SESSION['ecole']['user'])
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$ecole = $ecole->fetch(PDO::FETCH_ASSOC);


if (empty($ecole) ||
	$ecole['etat_inscription'] != 'ouverte' &&
	$ecole['etat_inscription'] != 'close')
	die(require DIR.'templates/_error.php');


$sports = $pdo->query('SELECT '.
		's.id, '.
		's.sexe, '.
		's.quota_inscription, '.
		'COUNT(DISTINCT sp.id_participant) AS inscrits, '.
		'COUNT(DISTINCT t.id) AS special, '.
		'es.quota_max, '.
		's.sport '.
	'FROM ecoles_sports AS es '.
	'JOIN sports AS s ON '.
		's.id = es.id_sport '.
	'LEFT JOIN sportifs AS sp ON '.
		'sp.id_sport = s.id '.
	'LEFT JOIN tarifs_ecoles AS te ON '.
		'te.id_ecole = es.id_ecole '.
	'LEFT JOIN tarifs AS t ON '.
		't.id = te.id_tarif AND '.
		't.id_sport_special = s.id '.
	'WHERE '.
		'es.id_ecole = '.$_SESSION['ecole']['user'].' AND '.
		's.id NOT IN (SELECT '.
				'e.id_sport '.
			'FROM equipes AS e WHERE '.
				'e.id_ecole = '.$_SESSION['ecole']['user'].') '.
	'GROUP BY '.
		's.id '.
	'ORDER BY '.
		'sport ASC, '.
		'sexe ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sports = $sports->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$sportifs = $pdo->query('SELECT '.
		'p.id, '.
		'p.nom, '.
		'p.prenom, '.
		'p.sexe, '.
		't.id_sport_special, '.
		'GROUP_CONCAT(s.id_sport) AS id_sports '.
	'FROM participants AS p '.
	'LEFT JOIN sportifs AS s ON '.
		'id_participant = p.id AND '.
		's.id_ecole = p.id_ecole '.
	'LEFT JOIN tarifs AS t ON '.
		't.id = p.id_tarif '.
	'WHERE '.
		'sportif = 1 AND '.
		'p.id_ecole = '.$_SESSION['ecole']['user'].' AND '.
		'LENGTH(telephone) > 0 AND '.
		'p.id NOT IN (SELECT '.
				'id_capitaine '.
			'FROM equipes WHERE '.
				'id_ecole = '.$_SESSION['ecole']['user'].') '.
	'GROUP BY '.
		'p.id '.
	'ORDER BY '.
		'p.nom ASC, '.
		'p.prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sportifs = $sportifs->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$equipes = $pdo->query('SELECT '.
		's.id, '.
		'es.quota_max, '.
		's.sport, '.
		's.sexe, '.
		's.id_respo, '.
		'e.effectif, '.
		'e.id_capitaine AS cid, '.
		'p.nom AS cnom, '.
		'p.prenom AS cprenom, '.
		's.quota_inscription, '.
		'COUNT(DISTINCT spc.id_participant) AS inscrits, '.
		'COUNT(DISTINCT t.id) AS special, '.
		'COUNT(DISTINCT sp.id_participant) AS nb '.
	'FROM equipes AS e '.
	'JOIN ecoles_sports AS es ON '.
		'es.id_sport = e.id_sport AND '.
		'es.id_ecole = e.id_ecole '.
	'JOIN sports AS s ON '.
		's.id = e.id_sport '.
	'LEFT JOIN participants AS p ON '.
		'p.id = e.id_capitaine AND '.
		'p.sportif = 1 '.
	'LEFT JOIN sportifs AS sp ON '.
		'sp.id_ecole = es.id_ecole AND '.
		'sp.id_sport = es.id_sport '.
	'LEFT JOIN sportifs AS spc ON '.
		'spc.id_sport = s.id '.
	'LEFT JOIN tarifs_ecoles AS te ON '.
		'te.id_ecole = es.id_ecole '.
	'LEFT JOIN tarifs AS t ON '.
		't.id = te.id_tarif AND '.
		't.id_sport_special = s.id '.
	'WHERE '.
		'e.id_ecole = '.$_SESSION['ecole']['user'].' '.
	'GROUP BY '.
		's.id '.
	'ORDER BY '.
		'sport ASC, '.
		'sexe ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$equipes = $equipes->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


//Ajout d'une équipe
if (isset($_POST['add']) &&
	!empty($_POST['sport'][0]) &&
	!empty($_POST['capitaine'][0]) &&
	!empty($_POST['effectif'][0]) &&
	intval($_POST['sport'][0]) && 
	intval($_POST['capitaine'][0]) && 
	intval($_POST['effectif'][0]) &&
	in_array($_POST['sport'][0], array_keys($sports)) &&
	in_array($_POST['capitaine'][0], array_keys($sportifs)) && (
		$sportifs[$_POST['capitaine'][0]]['sexe'] == $sports[$_POST['sport'][0]]['sexe'] ||
		$sports[$_POST['sport'][0]]['sexe'] == 'm') && (
		empty($sportifs[$_POST['capitaine'][0]]['id_sports']) ||
		in_array_multiple(explode(',', $sportifs[$_POST['capitaine'][0]]['id_sports']), explode(',', $multiples)) &&
		in_array($_POST['sport'][0], explode(',', $multiples))) &&
	$_POST['effectif'][0] >= 1 &&
	$_POST['effectif'][0] <= $sports[$_POST['sport'][0]]['quota_max'] && (
		empty($sports[$_POST['sport'][0]]['quota_inscription']) ||
		$sports[$_POST['sport'][0]]['quota_inscription'] - $sports[$_POST['sport'][0]]['inscrits'] > 0)) {

	if ($sports[$_POST['sport'][0]]['special'] &&
		$sportifs[$_POST['capitaine'][0]]['id_sport_special'] != $_POST['sport'][0])
		$sport_special = true;


	else {

		$pdo->exec('INSERT INTO equipes SET '.
			'id_capitaine = '.(int) $_POST['capitaine'][0].', '.
			'effectif = '.(int) $_POST['effectif'][0].', '.
			'id_sport = '.(int) $_POST['sport'][0].', '.
			'id_ecole = '.$_SESSION['ecole']['user']);
		

		//On ajoute le capitaine dans l'équipe
		$pdo->exec('INSERT INTO sportifs SET '.
			'id_participant = '.(int) $_POST['capitaine'][0].', '.
			'id_sport = '.(int) $_POST['sport'][0].', '.
			'id_ecole = '.$_SESSION['ecole']['user']);

		$add = true;

	}
}


//On récupère l'indice du champ concerné
if ((!empty($_POST['delete']) || 
	!empty($_POST['edit'])) &&
	isset($_POST['sport']) &&
	is_array($_POST['sport']))
	$i = array_search(empty($_POST['delete']) ?
		$_POST['edit'] :
		$_POST['delete'],
		$_POST['sport']);


//On edite une équipe
if (!empty($i) &&
	empty($_POST['delete']) &&
	!empty($_POST['sport'][$i]) &&
	!empty($_POST['capitaine'][$i]) &&
	!empty($_POST['effectif'][$i]) &&
	intval($_POST['sport'][$i]) &&
	intval($_POST['capitaine'][$i]) &&
	intval($_POST['effectif'][$i]) && 
	in_array($_POST['sport'][$i], array_keys($equipes)) && (
		in_array($_POST['capitaine'][$i], array_keys($sportifs)) && (
			$sportifs[$_POST['capitaine'][$i]]['sexe'] == $equipes[$_POST['sport'][$i]]['sexe'] ||
			$equipes[$_POST['sport'][$i]]['sexe'] == 'm') && ( 
			empty($sportifs[$_POST['capitaine'][$i]]['id_sports']) ||
			in_array($_POST['sport'][$i], explode(',', $sportifs[$_POST['capitaine'][$i]]['id_sports'])) ||
			in_array($_POST['sport'][$i], explode(',', $multiples)) &&
			in_array_multiple(explode(',', $sportifs[$_POST['capitaine'][$i]]['id_sports']), explode(',', $multiples))) ||
		$_POST['capitaine'][$i] == $equipes[$_POST['sport'][$i]]['cid']) &&
	$_POST['effectif'][$i] >= max(1, $equipes[$_POST['sport'][$i]]['nb']) &&
	$_POST['effectif'][$i] <= $equipes[$_POST['sport'][$i]]['quota_max']&& (
		empty($equipes[$_POST['sport'][$i]]['quota_inscription']) ||
		$_POST['capitaine'][$i] == $equipes[$_POST['sport'][$i]]['cid'] ||
		$equipes[$_POST['sport'][$i]]['quota_inscription'] - $equipes[$_POST['sport'][$i]]['inscrits'] > 0)) {

	if ($equipes[$_POST['sport'][$i]]['special'] &&
		$_POST['capitaine'][$i] != $equipes[$_POST['sport'][$i]]['cid'] &&
		$sportifs[$_POST['capitaine'][$i]]['id_sport_special'] != $_POST['sport'][$i])
		$sport_special = true;


	//Mise à jour de l'équipe
	else {

		$pdo->exec('UPDATE equipes SET '.
				'id_capitaine = '.(int) $_POST['capitaine'][$i].', '.
				'effectif = '.(int) $_POST['effectif'][$i].' '.
			'WHERE '.
				'id_sport = '.(int) $_POST['sport'][$i].' AND '.
				'id_ecole = '.$_SESSION['ecole']['user']);
		
		//On ajoute le capitaine dans l'équipe
		//Mais on n'enlève pas l'ancien
		if ($_POST['capitaine'][$i] != $equipes[$_POST['sport'][$i]]['cid'] &&
			!in_array($_POST['sport'][$i], explode(',', $sportifs[$_POST['capitaine'][$i]]['id_sports'])))
			$pdo->exec('INSERT INTO sportifs SET '.
				'id_participant = '.(int) $_POST['capitaine'][$i].', '.
				'id_sport = '.(int) $_POST['sport'][$i].', '.
				'id_ecole = '.$_SESSION['ecole']['user']);

		$modify = true;

	}
}


//On supprime une équipe
else if (!empty($i) &&
	!empty($_POST['delete']) &&
	!empty($_POST['sport'])) {

	$pdo->exec('DELETE FROM equipes '.
		'WHERE '.
			'id_ecole = '.$_SESSION['ecole']['user'].' AND '.
			'id_sport = '.$_POST['sport'][$i]);
	
	$pdo->query('DELETE FROM sportifs '.
		'WHERE '.
			'id_ecole = '.$_SESSION['ecole']['user'].' AND '.
			'id_sport = '.$_POST['sport'][$i]);

	$delete = true;
}

if (!empty($add) ||
	!empty($modify) ||
	!empty($delete)) {

	$sports = $pdo->query('SELECT '.
			's.id, '.
			's.sexe, '.
			's.quota_inscription, '.
			'COUNT(DISTINCT sp.id_participant) AS inscrits, '.
			'COUNT(DISTINCT t.id) AS special, '.
			'es.quota_max, '.
			's.sport '.
		'FROM ecoles_sports AS es '.
		'JOIN sports AS s ON '.
			's.id = es.id_sport '.
		'LEFT JOIN sportifs AS sp ON '.
			'sp.id_sport = s.id '.
		'LEFT JOIN tarifs_ecoles AS te ON '.
			'te.id_ecole = es.id_ecole '.
		'LEFT JOIN tarifs AS t ON '.
			't.id = te.id_tarif AND '.
			't.id_sport_special = s.id '.
		'WHERE '.
			'es.id_ecole = '.$_SESSION['ecole']['user'].' AND '.
			's.id NOT IN (SELECT '.
					'e.id_sport '.
				'FROM equipes AS e WHERE '.
					'e.id_ecole = '.$_SESSION['ecole']['user'].') '.
		'GROUP BY '.
			's.id '.
		'ORDER BY '.
			'sport ASC, '.
			'sexe ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$sports = $sports->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


	$sportifs = $pdo->query('SELECT '.
			'p.id, '.
			'p.nom, '.
			'p.prenom, '.
			'p.sexe, '.
			't.id_sport_special, '.
			'GROUP_CONCAT(s.id_sport) AS id_sports '.
		'FROM participants AS p '.
		'LEFT JOIN sportifs AS s ON '.
			'id_participant = p.id AND '.
			's.id_ecole = p.id_ecole '.
		'LEFT JOIN tarifs AS t ON '.
			't.id = p.id_tarif '.
		'WHERE '.
			'sportif = 1 AND '.
			'p.id_ecole = '.$_SESSION['ecole']['user'].' AND '.
			'LENGTH(telephone) > 0 AND '.
			'p.id NOT IN (SELECT '.
					'id_capitaine '.
				'FROM equipes WHERE '.
					'id_ecole = '.$_SESSION['ecole']['user'].') '.
		'GROUP BY '.
			'p.id '.
		'ORDER BY '.
			'p.nom ASC, '.
			'p.prenom ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$sportifs = $sportifs->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


	$equipes = $pdo->query('SELECT '.
			's.id, '.
			'es.quota_max, '.
			's.sport, '.
			's.sexe, '.
			's.id_respo, '.
			'e.effectif, '.
			'e.id_capitaine AS cid, '.
			'p.nom AS cnom, '.
			'p.prenom AS cprenom, '.
			's.quota_inscription, '.
			'COUNT(DISTINCT spc.id_participant) AS inscrits, '.
			'COUNT(DISTINCT t.id) AS special, '.
			'COUNT(DISTINCT sp.id_participant) AS nb '.
		'FROM equipes AS e '.
		'JOIN ecoles_sports AS es ON '.
			'es.id_sport = e.id_sport AND '.
			'es.id_ecole = e.id_ecole '.
		'JOIN sports AS s ON '.
			's.id = e.id_sport '.
		'LEFT JOIN participants AS p ON '.
			'p.id = e.id_capitaine AND '.
			'p.sportif = 1 '.
		'LEFT JOIN sportifs AS sp ON '.
			'sp.id_ecole = es.id_ecole AND '.
			'sp.id_sport = es.id_sport '.
		'LEFT JOIN sportifs AS spc ON '.
			'spc.id_sport = s.id '.
		'LEFT JOIN tarifs_ecoles AS te ON '.
			'te.id_ecole = es.id_ecole '.
		'LEFT JOIN tarifs AS t ON '.
			't.id = te.id_tarif AND '.
			't.id_sport_special = s.id '.
		'WHERE '.
			'e.id_ecole = '.$_SESSION['ecole']['user'].' '.
		'GROUP BY '.
			's.id '.
		'ORDER BY '.
			'sport ASC, '.
			'sexe ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$equipes = $equipes->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);



}


//Inclusion du bon fichier de template
require DIR.'templates/ecoles/equipes.php';
