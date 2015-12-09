<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/ecoles/participants.php *************************/
/* Inscription des participants ****************************/
/* *********************************************************/
/* Dernière modification : le 08/12/14 *********************/
/* *********************************************************/


if (empty($_SESSION['ecole']) ||
	empty($_SESSION['ecole']['user']))
	die(header('location:'.url('accueil', false, false)));

$ecole = $pdo->query('SELECT '.
		'e.*, '.
		'(SELECT COUNT(p1.id) FROM participants AS p1 WHERE p1.id_ecole = e.id) AS quota_inscriptions, '.
		'(SELECT COUNT(p2.id) FROM participants AS p2 WHERE p2.id_ecole = e.id AND p2.sportif = 1) AS quota_sportif_view, '.
		'(SELECT COUNT(p3.id) FROM participants AS p3 WHERE p3.id_ecole = e.id AND p3.pompom = 1) AS quota_pompom_view, '.
		'(SELECT COUNT(p5.id) FROM participants AS p5 WHERE p5.id_ecole = e.id AND p5.cameraman = 1) AS quota_cameraman_view, '.
		'(SELECT COUNT(p4.id) FROM participants AS p4 WHERE p4.id_ecole = e.id AND p4.fanfaron = 1) AS quota_fanfaron_view, '.
		'(SELECT COUNT(p6.id) FROM participants AS p6 WHERE p6.id_ecole = e.id AND p6.pompom = 1 AND p6.sportif = 0) AS quota_pompom_nonsportif_view, '.
		'(SELECT COUNT(p7.id) FROM participants AS p7 WHERE p7.id_ecole = e.id AND p7.fanfaron = 1 AND p7.sportif = 0) AS quota_fanfaron_nonsportif_view, '.
		'(SELECT COUNT(p10.id) FROM participants AS p10 WHERE p10.id_ecole = e.id AND p10.cameraman = 1 AND p10.sportif = 0) AS quota_cameraman_nonsportif_view, '.
		'(SELECT COUNT(p8.id) FROM participants AS p8 JOIN tarifs AS t8 ON t8.id = p8.id_tarif AND t8.logement = 1 WHERE p8.id_ecole = e.id AND p8.sexe = "f") AS quota_filles_logees_view, '.
		'(SELECT COUNT(p9.id) FROM participants AS p9 JOIN tarifs AS t9 ON t9.id = p9.id_tarif AND t9.logement = 1 WHERE p9.id_ecole = e.id AND p9.sexe = "h") AS quota_garcons_loges_view '.
	'FROM ecoles AS e '.
	'WHERE '.
		'e.id = '.(int) $_SESSION['ecole']['user'])
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$ecole = $ecole->fetch(PDO::FETCH_ASSOC);


if (empty($ecole) ||
	!in_array($ecole['etat_inscription'], array('ouverte', 'close')))
	die(require DIR.'templates/_error.php');

if ($ecole['etat_inscription'] == 'close')
	die(header('location:'.url('ecole/recapitulatif', false, false)));


$places_filles_logees = $ecole['quota_filles_logees'] - $ecole['quota_filles_logees_view'];
$places_garcons_loges = $ecole['quota_garcons_loges'] - $ecole['quota_garcons_loges_view'];
$places_sportif = $ecole['quota_sportif'] - $ecole['quota_sportif_view'];
$places_pompom = $ecole['quota_pompom'] - $ecole['quota_pompom_view'];
$places_cameraman = $ecole['quota_cameraman'] - $ecole['quota_cameraman_view'];
$places_fanfaron = $ecole['quota_fanfaron'] - $ecole['quota_fanfaron_view'];


$recharges = $pdo->query('SELECT '.
		'r.id, '.
		'r.nom, '.
		'r.montant '.
	'FROM recharges AS r '.
	'ORDER BY '.
		'montant ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$recharges = $recharges->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$tarifs = $pdo->query('SELECT '.
		't.type AS _group, '.
		't.type, '.
		't.for_pompom, '.
		't.for_cameraman, '.
		't.for_fanfaron, '.
		't.id, '.
		't.tarif, '.
		't.nom, '.
		't.description, '.
		't.id_sport_special, '.
		's.sport, '.
		's.sexe, '.
		't.logement '.
	'FROM tarifs AS t '.
	'JOIN tarifs_ecoles AS te ON '.
		'te.id_ecole = '.$_SESSION['ecole']['user'].' AND '.
		'te.id_tarif = t.id '.
	'LEFT JOIN sports AS s ON '.
		's.id = t.id_sport_special '.
	'ORDER BY '.
		'type ASC, '.
		'ordre ASC, '.
		'nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$tarifs_groupes = $tarifs->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
$tarifs = [];
foreach ($tarifs_groupes as $tarifs_groupe)
	$tarifs = array_merge($tarifs, $tarifs_groupe);


//Ajout d'un participant
if (isset($_POST['add']) &&
	!empty($_POST['nom'][0]) &&
	!empty($_POST['prenom'][0]) &&
	!empty($_POST['sexe'][0]) && (
		isset($_POST['sportif']) && 
		in_array('0', $_POST['sportif']) &&
		!empty($_POST['licence'][0]) ||
		empty($_POST['sportif']) ||
		!in_array('0', $_POST['sportif'])) &&
	in_array($_POST['sexe'][0], ['h', 'f']) &&
	!empty($_POST['tarif'][0]) &&
	!empty($_POST['recharge'][0]) &&
	intval($_POST['tarif'][0]) &&
	intval($_POST['recharge'][0]) &&
	in_array($_POST['recharge'][0], array_keys($recharges))) {

	if (!isset($_POST['sportif'])) $_POST['sportif'] = array();
	if (!isset($_POST['pompom'])) $_POST['pompom'] = array();
	if (!isset($_POST['cameraman'])) $_POST['cameraman'] = array();
	if (!isset($_POST['fanfaron'])) $_POST['fanfaron'] = array();

	$type = in_array('0', $_POST['sportif']) ? 'sportif' : 'nonsportif';


	if ($type == 'sportif' ||
		in_array('0', $_POST['pompom']) ||
		in_array('0', $_POST['fanfaron']) ||
		in_array('0', $_POST['cameraman'])) {
 		
		$tarif = $pdo->query('SELECT '.
				'logement '.
			'FROM tarifs AS t '.
			'JOIN tarifs_ecoles AS te ON '.
				'te.id_tarif = t.id AND '.
				'te.id_ecole = '.$_SESSION['ecole']['user'].' '.
			'WHERE '.
				't.id = '.$_POST['tarif'][0].' AND '.
				't.type = "'.$type.'"')
			or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
		$tarif = $tarif->fetch(PDO::FETCH_ASSOC);
		

		if (is_array($tarif) &&
			!($tarif['logement'] && 
			  $ecole['quota_logement_on'] && (
					$_POST['sexe'][0] == 'f' &&
					$places_filles_logees <= 0 ||
					$_POST['sexe'][0] == 'h' &&
					$places_garcons_loges <= 0) ||
				in_array('0', $_POST['sportif']) &&
				$places_sportif <= 0 ||
				in_array('0', $_POST['pompom']) &&
				$places_pompom <= 0 ||
				in_array('0', $_POST['fanfaron']) &&
				$places_fanfaron <= 0 ||
				in_array('0', $_POST['cameraman']) &&
				$places_cameraman <= 0)) {

			$logeur = !$ecole['ecole_lyonnaise'] &&
				!$tarif['logement'] &&
				!empty($_POST['logeur']) ? 
				$_POST['logeur'] : 
				'';

			$pdo->exec('INSERT INTO participants SET '.
				'nom = "'.secure($_POST['nom'][0]).'", '.
				'prenom = "'.secure($_POST['prenom'][0]).'", '.
				'sexe = "'.secure($_POST['sexe'][0]).'", '.
				'telephone = "'.secure($_POST['telephone'][0]).'", '.
				'licence = "'.($type == 'sportif' ? secure($_POST['licence'][0]) : '').'", '.
				'sportif = '.(in_array('0', $_POST['sportif']) ? '1' : '0').', '.
				'pompom = '.(in_array('0', $_POST['pompom']) ? '1' : '0').', '.
				'fanfaron = '.(in_array('0', $_POST['fanfaron']) ? '1' : '0').', '.
				'cameraman = '.(in_array('0', $_POST['cameraman']) ? '1' : '0').', '.
				'id_tarif = '.abs((int) $_POST['tarif'][0]).', '.
				'id_recharge = '.abs((int) $_POST['recharge'][0]).', '.
				'id_ecole = '.$_SESSION['ecole']['user'].', '.
				'logeur = "'.secure($logeur).'", '.
				'date_inscription = NOW(), '.
				'date_modification = NOW()');

			$add = true;
		}
	}
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
	!empty($_POST['nom'][$i]) &&
	!empty($_POST['prenom'][$i]) &&
	!empty($_POST['sexe'][$i]) && (
		isset($_POST['sportif']) && 
		in_array($_POST['id'][$i], $_POST['sportif']) &&
		!empty($_POST['licence'][$i]) ||
		empty($_POST['sportif']) ||
		!in_array($_POST['id'][$i], $_POST['sportif'])) &&
	in_array($_POST['sexe'][$i], ['h', 'f']) &&
	!empty($_POST['tarif'][$i]) &&
	!empty($_POST['recharge'][$i]) &&
	intval($_POST['tarif'][$i]) &&
	intval($_POST['recharge'][$i]) &&
	in_array($_POST['recharge'][$i], array_keys($recharges))) {
	
	if (!isset($_POST['sportif'])) $_POST['sportif'] = array();
	if (!isset($_POST['pompom'])) $_POST['pompom'] = array();
	if (!isset($_POST['cameraman'])) $_POST['cameraman'] = array();
	if (!isset($_POST['fanfaron'])) $_POST['fanfaron'] = array();

	$type = in_array($_POST['id'][$i], $_POST['sportif']) ? 'sportif' : 'nonsportif';

	if ($type == 'sportif' ||
		in_array($_POST['id'][$i], $_POST['pompom']) ||
		in_array($_POST['id'][$i], $_POST['fanfaron']) ||
		in_array($_POST['id'][$i], $_POST['cameraman'])) {

		$found = $pdo->query('SELECT '.
				'sportif, '.
				'pompom, '.
				'cameraman, '.
				'fanfaron, '.
				'logement, '.
				'id_sport_special, '.
				'sexe, '.
				'e.id_sport AS eid '.
			'FROM participants AS p '.
			'JOIN tarifs AS t ON '.
				't.id = p.id_tarif '.
			'LEFT JOIN equipes AS e ON '.
				'e.id_capitaine = p.id '.
			'WHERE '.
				'p.id_ecole = '.$_SESSION['ecole']['user'].' AND '.
				'p.id = '.$_POST['id'][$i])
			or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
		$found = $found->fetch(PDO::FETCH_ASSOC);

		if (is_array($found) && (
				$type == 'sportif' || 
				$type == 'nonsportif' && 
				empty($found['eid']))) {
		
			$tarif = $pdo->query('SELECT '.
					'logement, '.
					'id_sport_special '.
				'FROM tarifs AS t '.
				'JOIN tarifs_ecoles AS te ON '.
					'te.id_tarif = t.id AND '.
					'te.id_ecole = '.$_SESSION['ecole']['user'].' '.
				'WHERE '.
					't.id = '.$_POST['tarif'][$i].' AND '.
					't.type = "'.$type.'"')
				or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
			$tarif = $tarif->fetch(PDO::FETCH_ASSOC);
			
			if ($found['logement'] && $found['sexe'] == 'f') $places_filles_logees++;
			if ($found['logement'] && $found['sexe'] == 'h') $places_garcons_loges++;
            if ($found['sportif']) $places_sportif++;
            if ($found['pompom']) $places_pompom++;
            if ($found['cameraman']) $places_cameraman++;
            if ($found['fanfaron']) $places_fanfaron++;


			if (is_array($tarif) &&
				!($tarif['logement'] && 
				  $ecole['quota_logement_on'] && (
						$_POST['sexe'][$i] == 'f' &&
						$places_filles_logees <= 0 ||
						$_POST['sexe'][$i] == 'h' &&
						$places_garcons_loges <= 0) ||
					in_array($_POST['id'][$i], $_POST['sportif']) &&
					$places_sportif <= 0 ||
					in_array($_POST['id'][$i], $_POST['pompom']) &&
					$places_pompom <= 0 ||
					in_array($_POST['id'][$i], $_POST['fanfaron']) &&
					$places_fanfaron <= 0 ||
					in_array($_POST['id'][$i], $_POST['cameraman']) &&
					$places_cameraman <= 0) &&
				!($cap_sport_special = !empty($found['eid']) && 
				  $found['eid'] == $found['id_sport_special'] &&
				  $found['id_sport_special'] != $tarif['id_sport_special'])) {

				$logeur = !$ecole['ecole_lyonnaise'] &&
					!$tarif['logement'] &&
					!empty($_POST['logeur']) ? 
					$_POST['logeur'] : 
					'';
				
				$pdo->exec('UPDATE participants SET '.
						'nom = "'.secure($_POST['nom'][$i]).'", '.
						'prenom = "'.secure($_POST['prenom'][$i]).'", '.
						'sexe = "'.secure($_POST['sexe'][$i]).'", '.
						'telephone = "'.secure($_POST['telephone'][$i]).'", '.
						'licence = "'.($type == 'sportif' ? secure($_POST['licence'][$i]) : '').'", '.
						'sportif = '.(in_array($_POST['id'][$i], $_POST['sportif']) ? '1' : '0').', '.
						'pompom = '.(in_array($_POST['id'][$i], $_POST['pompom']) ? '1' : '0').', '.
						'fanfaron = '.(in_array($_POST['id'][$i], $_POST['fanfaron']) ? '1' : '0').', '.
						'cameraman = '.(in_array($_POST['id'][$i], $_POST['cameraman']) ? '1' : '0').', '.
						'id_tarif = '.abs((int) $_POST['tarif'][$i]).', '.
						'id_recharge = '.abs((int) $_POST['recharge'][$i]).', '.
						'logeur = "'.secure($logeur).'", '.
						'date_modification = NOW() '.
					'WHERE '.
						'id_ecole = '.$_SESSION['ecole']['user'].' AND '.
						'id = '.$_POST['id'][$i]);


				if ($tarif['id_sport_special'] != $found['id_sport_special'] &&
					!empty($found['id_sport_special']))
					$pdo->exec('DELETE FROM sportifs WHERE '.
						'id_participant = '.$_POST['id'][$i].' AND '.
						'id_sport = '.$found['id_sport_special'].' AND '.
						'id_ecole = '.$_SESSION['ecole']['user']);


				if ($type == 'nonsportif')
					$pdo->exec('DELETE FROM sportifs WHERE '.
						'id_participant = '.$_POST['id'][$i].' AND '.
						'id_ecole = '.$_SESSION['ecole']['user']);

				$modify = true;
			}
		}
	}
}


//On supprime un participant
else if (isset($i) &&
	!empty($_POST['delete'])) {

	$pdo->exec('DELETE FROM participants '.
		'WHERE '.
			'id_ecole = '.$_SESSION['ecole']['user'].' AND '.
			'id = '.abs((int) $_POST['id'][$i])) or die(print_r($pdo->errorInfo()));

	$delete = true;
}


if (!empty($add) ||
	!empty($modify) ||
	!empty($delete)) {

	$ecole = $pdo->query('SELECT '.
			'e.*, '.
			'(SELECT COUNT(p1.id) FROM participants AS p1 WHERE p1.id_ecole = e.id) AS quota_inscriptions, '.
			'(SELECT COUNT(p2.id) FROM participants AS p2 WHERE p2.id_ecole = e.id AND p2.sportif = 1) AS quota_sportif_view, '.
			'(SELECT COUNT(p3.id) FROM participants AS p3 WHERE p3.id_ecole = e.id AND p3.pompom = 1) AS quota_pompom_view, '.
			'(SELECT COUNT(p4.id) FROM participants AS p4 WHERE p4.id_ecole = e.id AND p4.fanfaron = 1) AS quota_fanfaron_view, '.
			'(SELECT COUNT(p5.id) FROM participants AS p5 WHERE p5.id_ecole = e.id AND p5.cameraman = 1) AS quota_cameraman_view, '.
			'(SELECT COUNT(p6.id) FROM participants AS p6 WHERE p6.id_ecole = e.id AND p6.pompom = 1 AND p6.sportif = 0) AS quota_pompom_nonsportif_view, '.
			'(SELECT COUNT(p7.id) FROM participants AS p7 WHERE p7.id_ecole = e.id AND p7.fanfaron = 1 AND p7.sportif = 0) AS quota_fanfaron_nonsportif_view, '.
			'(SELECT COUNT(p10.id) FROM participants AS p10 WHERE p10.id_ecole = e.id AND p10.cameraman = 1 AND p10.sportif = 0) AS quota_cameraman_nonsportif_view, '.
			'(SELECT COUNT(p8.id) FROM participants AS p8 JOIN tarifs AS t8 ON t8.id = p8.id_tarif AND t8.logement = 1 WHERE p8.id_ecole = e.id AND p8.sexe = "f") AS quota_filles_logees_view, '.
			'(SELECT COUNT(p9.id) FROM participants AS p9 JOIN tarifs AS t9 ON t9.id = p9.id_tarif AND t9.logement = 1 WHERE p9.id_ecole = e.id AND p9.sexe = "h") AS quota_garcons_loges_view '.
		'FROM ecoles AS e '.
		'WHERE '.
			'e.id = '.(int) $_SESSION['ecole']['user'])
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$ecole = $ecole->fetch(PDO::FETCH_ASSOC);

}


$participants = $pdo->query('SELECT '.
		'p.*, '.
		'e.id_sport AS eid, '.
		't.logement '.
	'FROM participants AS p '.
	'LEFT JOIN equipes AS e ON '.
		'e.id_capitaine = p.id '.
	'JOIN tarifs AS t ON '.
		't.id = p.id_tarif '.
	'WHERE '.
		'p.id_ecole = '.$_SESSION['ecole']['user'].' '.
	'GROUP BY '.
		'p.id '.
	'ORDER BY '.
		'nom ASC, '.
		'prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$participants = $participants->fetchAll(PDO::FETCH_ASSOC);


//Inclusion du bon fichier de template
require DIR.'templates/ecoles/participants.php';
