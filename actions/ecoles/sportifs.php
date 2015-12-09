<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/ecoles/sportifs.php *****************************/
/* Edition des sportifs ************************************/
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


$sportifs = $pdo->query('SELECT '.
		'p.id, '.
		'p.nom, '.
		'p.prenom, '.
		'p.sexe, '.
		't.id_sport_special, '.
		'GROUP_CONCAT(s.id_sport) AS id_sports '.
	'FROM participants AS p '.
	'LEFT JOIN sportifs AS s ON '.
		's.id_participant = p.id AND '.
		's.id_ecole = p.id_ecole '.
	'LEFT JOIN tarifs AS t ON '.
		't.id = p.id_tarif '.
	'WHERE '.
		'sportif = 1 AND '.
		'p.id_ecole = '.$_SESSION['ecole']['user'].' '.
	'GROUP BY '.
		'p.id '.
	'ORDER BY '.
		'p.nom ASC, '.
		'p.prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sportifs = $sportifs->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$sports_doubles = '0 ';
foreach (explode(',', $multiples) as $sid) 
	if (!empty($sid))
		$sports_doubles .= ' OR s.id = '.$sid;

$sports_doubles = $pdo->query($s = 'SELECT '.
		's.id, '.
		's.sport, '.
		's.sexe '.
	'FROM sports AS s '.
	'WHERE '.$sports_doubles)
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sports_doubles = $sports_doubles->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$equipes_sportifs = $pdo->query('SELECT '.
		's.id, '.
		'es.quota_max, '.
		's.quota_inscription, '.
		'COUNT(DISTINCT spc.id_participant) AS inscrits, '.
		's.sport, '.
		's.sexe, '.
		's.id_respo, '.
		'e.effectif, '.
		'e.id_capitaine AS cid, '.
		'p.nom AS pnom, '.
		'p.prenom AS pprenom, '.
		'p.sexe AS psexe, '.
		'p.licence AS plicence, '.
		'COUNT(DISTINCT t.id) AS special, '.
		'p.id AS pid '.
	'FROM equipes AS e '.
	'JOIN ecoles_sports AS es ON '.
		'es.id_sport = e.id_sport AND '.
		'es.id_ecole = e.id_ecole '.
	'JOIN sports AS s ON '.
		's.id = e.id_sport '.
	'LEFT JOIN sportifs AS sp ON '.
		'sp.id_ecole = es.id_ecole AND '.
		'sp.id_sport = es.id_sport '.
	'LEFT JOIN participants AS p ON '.
		'p.id_ecole = e.id_ecole AND '.
		'p.id = sp.id_participant '.
	'LEFT JOIN sportifs AS spc ON '.
		'spc.id_sport = s.id '.
	'LEFT JOIN tarifs_ecoles AS te ON '.
		'te.id_ecole = e.id_ecole '.
	'LEFT JOIN tarifs AS t ON '.
		't.id = te.id_tarif AND '.
		't.id_sport_special = s.id '.
	'WHERE '.
		'e.id_ecole = '.$_SESSION['ecole']['user'].' '.
	'GROUP BY '.
		's.id, p.id '.
	'ORDER BY '.
		's.sport ASC, '.
		's.sexe ASC, '.
		'p.nom ASC, '.
		'p.prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$equipes_sportifs = $equipes_sportifs->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);


//Ajout d'un sportif
if (isset($_POST['add']) &&
	!empty($_POST['sport']) &&
	in_array($_POST['sport'], array_keys($equipes_sportifs)) &&
	!empty($_POST['sportif'][0]) &&
	in_array($_POST['sportif'][0], array_keys($sportifs)) && (
		empty($sportifs[$_POST['sportif'][0]]['id_sports']) ||
		in_array($_POST['sport'], explode(',', $multiples)) &&
		in_array_multiple(explode(',', $sportifs[$_POST['sportif'][0]]['id_sports']), explode(',', $multiples)) &&
		!in_array($_POST['sport'], explode(',', $sportifs[$_POST['sportif'][0]]['id_sports']))) &&
	$equipes_sportifs[$_POST['sport']][0]['effectif'] - count($equipes_sportifs[$_POST['sport']]) && (
		empty($equipes_sportifs[$_POST['sport']][0]['quota_inscription']) ||
		$equipes_sportifs[$_POST['sport']][0]['quota_inscription'] - $equipes_sportifs[$_POST['sport']][0]['inscrits'] > 0)) {

	if ($equipes_sportifs[$_POST['sport']][0]['special'] &&
		$sportifs[$_POST['sportif'][0]]['id_sport_special'] != $_POST['sport'])
		$sport_special = true;

	//On ajoute le sportif dans l'équipe
	else {
		
		$pdo->exec('INSERT INTO sportifs SET '.
			'id_participant = '.(int) $_POST['sportif'][0].', '.
			'id_sport = '.(int) $_POST['sport'].', '.
			'id_ecole = '.$_SESSION['ecole']['user']) or die(print_r($pdo->errorInfo()));;

		$add = true;
	}
}


//On récupère l'indice du champ concerné
if (!empty($_POST['delete']) &&
	!empty($_POST['sport']) &&
	in_array($_POST['sport'], array_keys($equipes_sportifs)) &&
	isset($_POST['sportif']) &&
	is_array($_POST['sportif']))
	$i = array_search($_POST['delete'], $_POST['sportif']);


//On supprime un sportif
if (!empty($i) &&
	intval($_POST['sportif'][$i]) &&
	$equipes_sportifs[$_POST['sport']][0]['cid'] != $_POST['sportif'][$i] &&
	$_POST['sportif'][$i] > 0) {

	$pdo->query('DELETE FROM sportifs '.
		'WHERE '.
			'id_ecole = '.$_SESSION['ecole']['user'].' AND '.
			'id_sport = '.$_POST['sport'].' AND '.
			'id_participant = '.(int) $_POST['sportif'][$i]);

	$delete = true;
}


if (!empty($add) ||
	!empty($delete)) {

	$sportifs = $pdo->query('SELECT '.
			'p.id, '.
			'p.nom, '.
			'p.prenom, '.
			'p.sexe, '.
			't.id_sport_special, '.
			'GROUP_CONCAT(s.id_sport) AS id_sports '.
		'FROM participants AS p '.
		'LEFT JOIN sportifs AS s ON '.
			's.id_participant = p.id AND '.
			's.id_ecole = p.id_ecole '.
		'LEFT JOIN tarifs AS t ON '.
			't.id = p.id_tarif '.
		'WHERE '.
			'sportif = 1 AND '.
			'p.id_ecole = '.$_SESSION['ecole']['user'].' '.
		'GROUP BY '.
			'p.id '.
		'ORDER BY '.
			'p.nom ASC, '.
			'p.prenom ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$sportifs = $sportifs->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


	$equipes_sportifs = $pdo->query('SELECT '.
			's.id, '.
			'es.quota_max, '.
			's.quota_inscription, '.
			'COUNT(DISTINCT spc.id_participant) AS inscrits, '.
			's.sport, '.
			's.sexe, '.
			's.id_respo, '.
			'e.effectif, '.
			'e.id_capitaine AS cid, '.
			'p.nom AS pnom, '.
			'p.prenom AS pprenom, '.
			'p.sexe AS psexe, '.
			'p.licence AS plicence, '.
			'COUNT(DISTINCT t.id) AS special, '.
			'p.id AS pid '.
		'FROM equipes AS e '.
		'JOIN ecoles_sports AS es ON '.
			'es.id_sport = e.id_sport AND '.
			'es.id_ecole = e.id_ecole '.
		'JOIN sports AS s ON '.
			's.id = e.id_sport '.
		'LEFT JOIN sportifs AS sp ON '.
			'sp.id_ecole = es.id_ecole AND '.
			'sp.id_sport = es.id_sport '.
		'LEFT JOIN participants AS p ON '.
			'p.id_ecole = e.id_ecole AND '.
			'p.id = sp.id_participant '.
		'LEFT JOIN sportifs AS spc ON '.
			'spc.id_sport = s.id '.
		'LEFT JOIN tarifs_ecoles AS te ON '.
			'te.id_ecole = e.id_ecole '.
		'LEFT JOIN tarifs AS t ON '.
			't.id = te.id_tarif AND '.
			't.id_sport_special = s.id '.
		'WHERE '.
			'e.id_ecole = '.$_SESSION['ecole']['user'].' '.
		'GROUP BY '.
			's.id, p.id '.
		'ORDER BY '.
			's.sport ASC, '.
			's.sexe ASC, '.
			'p.nom ASC, '.
			'p.prenom ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$equipes_sportifs = $equipes_sportifs->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);


}

//Inclusion du bon fichier de template
require DIR.'templates/ecoles/sportifs.php';
