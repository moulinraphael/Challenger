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


$ecole = $pdo->query($request_ecole = 'SELECT '.
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


$logement_on = (int) $ecole['quota_logement_on']; 
$filles_on = (int) $ecole['quota_filles_on']; 
$garcons_on = (int) $ecole['quota_garcons_on']; 
$pompom_on = (int) $ecole['quota_pompom_on']; 
$pompom_nonsportif_on = (int) $ecole['quota_pompom_nonsportif_on']; 
$cameraman_on = (int) $ecole['quota_cameraman_on']; 
$cameraman_nonsportif_on = (int) $ecole['quota_cameraman_nonsportif_on']; 
$fanfaron_on = (int) $ecole['quota_fanfaron_on']; 
$fanfaron_nonsportif_on = (int) $ecole['quota_fanfaron_nonsportif_on']; 

$places_inscription = $ecole['quota_total'] - $ecole['quota_inscriptions'];
$places_filles_logees = $ecole['quota_filles_logees'] - $ecole['quota_filles_logees_view']; 
$places_garcons_loges = $ecole['quota_garcons_loges'] - $ecole['quota_garcons_loges_view']; 
$places_logement = $ecole['quota_logement'] - $ecole['quota_garcons_loges_view'] - $ecole['quota_filles_logees_view']; 
$places_sportif = $ecole['quota_sportif'] - $ecole['quota_sportif_view']; 
$places_pompom = $ecole['quota_pompom'] - $ecole['quota_pompom_view']; 
$places_cameraman = $ecole['quota_cameraman'] - $ecole['quota_cameraman_view']; 
$places_fanfaron = $ecole['quota_fanfaron'] - $ecole['quota_fanfaron_view']; 
$places_pompom_nonsportif = $ecole['quota_pompom_nonsportif'] - $ecole['quota_pompom_nonsportif_view']; 
$places_cameraman_nonsportif = $ecole['quota_cameraman_nonsportif'] - $ecole['quota_cameraman_nonsportif_view']; 
$places_fanfaron_nonsportif = $ecole['quota_fanfaron_nonsportif'] - $ecole['quota_fanfaron_nonsportif_view']; 


$recharges = $pdo->query('SELECT '.
		'r.id, '.
		'r.nom, '.
		'r.montant '.
	'FROM recharges AS r '.
	'ORDER BY '.
		'montant ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$recharges = $recharges->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$nom_tarifs = $pdo->query('SELECT '.
		't.id, '.
		't.logement, '.
		't.tarif, '.
		't.nom '.
	'FROM tarifs AS t')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$nom_tarifs = $nom_tarifs->fetchAll(PDO::FETCH_ASSOC);


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
if (isset($_POST['add_participant']) &&
	!empty($_POST['nom']) &&
	!empty($_POST['prenom']) &&
	!empty($_POST['email']) &&
	isValidEmail($_POST['email']) &&
	isset($_POST['telephone']) && (
		!empty($_POST['sportif']) && 
		!empty($_POST['licence']) ||
		empty($_POST['sportif'])) &&
	!empty($_POST['tarif']) &&
	!empty($_POST['recharge']) &&
	intval($_POST['tarif']) &&
	intval($_POST['recharge']) &&
	in_array($_POST['recharge'], array_keys($recharges))) {

	$is_man = !empty($_POST['sexe']);
	$is_sportif = !empty($_POST['sportif']);
	$is_pompom = !empty($_POST['pompom']);
	$is_cameraman = !empty($_POST['cameraman']);
	$is_fanfaron = !empty($_POST['fanfaron']);
	$is_none = !$is_sportif && !$is_pompom &&
		!$is_cameraman && !$is_fanfaron;

	$is_tarif_valid = false;
	$tarif_logement = false;
	foreach ($tarifs as $tarif) {
		if ($tarif['id'] == $_POST['tarif'] &&
			$tarif['type'] == ($is_sportif ? 'sportif' : 'nonsportif')) {
			$is_tarif_valid = true;
			$tarif_logement = $tarif['logement'];
			break;
		}
	}

	if ($is_none || 
		!$is_tarif_valid) 
		$error = 'add_tarif';

	else if ($places_inscription <= 0 ||
		$tarif_logement && (
		$logement_on && $places_logement <= 0 ||
		$filles_on && !$is_man && $places_filles_logees <= 0 ||
		$garcons_on && $is_man && $places_garcons_loges <= 0) ||
		$is_sportif && $places_sportif <= 0 ||
		$pompom_on && $is_pompom && $places_pompom <= 0 ||
		$cameraman_on && $is_cameraman && $places_cameraman <= 0 ||
		$fanfaron_on && $is_fanfaron && $places_fanfaron <= 0 ||
		$pompom_nonsportif_on && $is_pompom && !$is_sportif && $places_pompom_nonsportif <= 0 ||
		$cameraman_nonsportif_on && $is_cameraman && !$is_sportif && $places_cameraman_nonsportif <= 0 ||
		$fanfaron_nonsportif_on && $is_fanfaron && !$is_sportif && $places_fanfaron_nonsportif <= 0) 
		$error = 'add_quotas';

	else {
		$logeur = !$ecole['ecole_lyonnaise'] &&
			!$tarif_logement &&
			!empty($_POST['logeur']) ? 
			$_POST['logeur'] : 
			'';

		$pdo->exec('INSERT INTO participants SET '.
			'nom = "'.secure($_POST['nom']).'", '.
			'prenom = "'.secure($_POST['prenom']).'", '.
			'sexe = "'.($is_man ? 'h' : 'f').'", '.
			'telephone = "'.secure($_POST['telephone']).'", '.
			'email = "'.secure($_POST['email']).'", '.
			'licence = "'.($is_sportif ? secure($_POST['licence']) : '').'", '.
			'sportif = '.($is_sportif ? '1' : '0').', '.
			'pompom = '.($is_pompom ? '1' : '0').', '.
			'fanfaron = '.($is_fanfaron ? '1' : '0').', '.
			'cameraman = '.($is_cameraman ? '1' : '0').', '.
			'id_tarif = '.abs((int) $_POST['tarif']).', '.
			'id_recharge = '.abs((int) $_POST['recharge']).', '.
			'id_ecole = '.$_SESSION['ecole']['user'].', '.
			'logeur = "'.secure($logeur).'", '.
			'date_inscription = NOW(), '.
			'date_modification = NOW()');

		$add = true;
	}

} else if (!empty($_POST['add_participant']))
	$error = 'add_champs';


//On veut éditer un participant, on récupère donc sa fiche
if (!empty($_POST['edit']) ||
	!empty($_POST['delete']) ||
	!empty($_POST['edit_participant']) &&
	!empty($_POST['id'])) {

	$found = $pdo->query('SELECT '.
			'p.id, '.
			'p.nom, '.
			'p.prenom, '.
			'p.sexe, '.
			'p.telephone, '.
			'p.email, '.
			'sportif, '.
			'pompom, '.
			'cameraman, '.
			'fanfaron, '.
			'p.licence, '.
			'p.logeur, '.
			'logement, '.
			'id_tarif, '.
			'id_recharge, '.
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
			'p.id = '.(int) (!empty($_POST['edit']) ? $_POST['edit'] : 
				(!empty($_POST['delete']) ? $_POST['delete'] : $_POST['id'])))
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$found = $found->fetch(PDO::FETCH_ASSOC);

	if (!empty($_POST['edit']))
		$participant_edit = $found;
}


//Edition d'un participant
if (isset($_POST['edit_participant']) &&
	!empty($_POST['id']) &&
	!empty($found) &&
	!empty($_POST['nom']) &&
	!empty($_POST['prenom']) &&
	!empty($_POST['email']) &&
	isValidEmail($_POST['email']) &&
	isset($_POST['telephone']) && (
		!empty($_POST['sportif']) && 
		!empty($_POST['licence']) ||
		empty($_POST['sportif'])) &&
	!empty($_POST['tarif']) &&
	!empty($_POST['recharge']) &&
	intval($_POST['tarif']) &&
	intval($_POST['recharge']) &&
	in_array($_POST['recharge'], array_keys($recharges))) {


	$was_man = $found['sexe'] == 'h';
	$was_sportif = $found['sportif'];
	$was_pompom = $found['pompom'];
	$was_cameraman = $found['cameraman'];
	$was_fanfaron = $found['fanfaron'];
	$was_loge = $found['logement'];
	$was_cap_special_sport = $found['eid'];


	$is_man = !empty($_POST['sexe']);
	$is_sportif = !empty($_POST['sportif']);
	$is_pompom = !empty($_POST['pompom']);
	$is_cameraman = !empty($_POST['cameraman']);
	$is_fanfaron = !empty($_POST['fanfaron']);
	$is_none = !$is_sportif && !$is_pompom &&
		!$is_cameraman && !$is_fanfaron;

	$is_tarif_valid = false;
	$tarif_logement = false;
	$tarif_sport_special = false;
	foreach ($tarifs as $tarif) {
		if ($tarif['id'] == $_POST['tarif'] &&
			$tarif['type'] == ($is_sportif ? 'sportif' : 'nonsportif')) {
			$is_tarif_valid = true;
			$tarif_logement = $tarif['logement'];
			$tarif_sport_special = $tarif['id_sport_special'];
			break;
		}
	}

	if ($was_sportif) $places_sportif++;
	if ($logement_on && $was_loge) $places_logement++;
	if ($filles_on && !$was_man && $was_loge) $places_filles_logees++;
	if ($garcons_on && $was_man && $was_loge) $places_garcons_loges++;
	if ($pompom_on && $was_pompom) $places_pompom++;
	if ($cameraman_on && $was_cameraman) $places_cameraman++;
	if ($fanfaron_on && $was_fanfaron) $places_fanfaron++;
	if ($pompom_nonsportif_on && $was_pompom && !$was_sportif) $places_pompom_nonsportif++;
	if ($cameraman_nonsportif_on && $was_cameraman && !$was_sportif) $places_cameraman_nonsportif++;
	if ($fanfaron_nonsportif_on && $was_fanfaron && !$was_sportif) $places_fanfaron_nonsportif++;

	if ($is_none || 
		!$is_tarif_valid) 
		$error = 'edit_tarif';

	else if ($places_inscription <= 0 ||
		$tarif_logement && (
		$logement_on && $places_logement <= 0 ||
		$filles_on && !$is_man && $places_filles_logees <= 0 ||
		$garcons_on && $is_man && $places_garcons_loges <= 0) ||
		$is_sportif && $places_sportif <= 0 ||
		$pompom_on && $is_pompom && $places_pompom <= 0 ||
		$cameraman_on && $is_cameraman && $places_cameraman <= 0 ||
		$fanfaron_on && $is_fanfaron && $places_fanfaron <= 0 ||
		$pompom_nonsportif_on && $is_pompom && !$is_sportif && $places_pompom_nonsportif <= 0 ||
		$cameraman_nonsportif_on && $is_cameraman && !$is_sportif && $places_cameraman_nonsportif <= 0 ||
		$fanfaron_nonsportif_on && $is_fanfaron && !$is_sportif && $places_fanfaron_nonsportif <= 0) 
		$error = 'edit_quotas';

	else if ($was_cap_special_sport &&
		$found['eid'] == $found['id_sport_special'] &&
		$found['id_sport_special'] != $tarif_sport_special)
		$error = 'edit_sport_special';

	else if ($was_sportif && $found['eid'] && !$is_sportif)
		$error = 'edit_cap';

	else {
		$logeur = !$ecole['ecole_lyonnaise'] &&
			!$tarif_logement &&
			!empty($_POST['logeur']) ? 
			$_POST['logeur'] : 
			'';

		$pdo->exec('UPDATE participants SET '.
				'nom = "'.secure($_POST['nom']).'", '.
				'prenom = "'.secure($_POST['prenom']).'", '.
				'sexe = "'.($is_man ? 'h' : 'f').'", '.
				'telephone = "'.secure($_POST['telephone']).'", '.
				'email = "'.secure($_POST['email']).'", '.
				'licence = "'.($is_sportif ? secure($_POST['licence']) : '').'", '.
				'sportif = '.($is_sportif ? '1' : '0').', '.
				'pompom = '.($is_pompom ? '1' : '0').', '.
				'fanfaron = '.($is_fanfaron ? '1' : '0').', '.
				'cameraman = '.($is_cameraman ? '1' : '0').', '.
				'id_tarif = '.abs((int) $_POST['tarif']).', '.
				'id_recharge = '.abs((int) $_POST['recharge']).', '.
				'id_ecole = '.$_SESSION['ecole']['user'].', '.
				'logeur = "'.secure($logeur).'", '.
				'date_modification = NOW() '.
			'WHERE '.
				'id_ecole = '.$_SESSION['ecole']['user'].' AND '.
				'id = '.$_POST['id']);


		if ($tarif_sport_special != $found['id_sport_special'] &&
			!empty($found['id_sport_special']))
			$pdo->exec('DELETE FROM sportifs WHERE '.
				'id_participant = '.$_POST['id'].' AND '.
				'id_sport = '.$found['id_sport_special'].' AND '.
				'id_ecole = '.$_SESSION['ecole']['user']);


		if (!$is_sportif)
			$pdo->exec('DELETE FROM sportifs WHERE '.
				'id_participant = '.$_POST['id'].' AND '.
				'id_ecole = '.$_SESSION['ecole']['user']);


		$modify = true;
	}

} else if (!empty($_POST['edit_participant']))
	$error = 'edit_champs';


//On supprime un participant
else if (!empty($_POST['delete']) &&
	!empty($found)) {

	if (!empty($found['eid']))
		$error = 'delete_cap';

	else {
		$pdo->exec('DELETE FROM participants '.
			'WHERE '.
				'id_ecole = '.$_SESSION['ecole']['user'].' AND '.
				'id = '.abs((int) $_POST['delete'])) or die(print_r($pdo->errorInfo()));

		$delete = true;
	}
}


if (!empty($add) ||
	!empty($modify) ||
	!empty($delete)) {

	$ecole = $pdo->query($request_ecole)
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
