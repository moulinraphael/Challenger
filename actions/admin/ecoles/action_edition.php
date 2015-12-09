<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/ecoles/action_edition.php *****************/
/* Edition d'une école *************************************/
/* *********************************************************/
/* Dernière modification : le 16/02/15 *********************/
/* *********************************************************/


$respos = $pdo->query('SELECT '.
		'a.id, '.
		'a.prenom, '.
		'a.nom '.
	'FROM admins AS a '.
	'ORDER BY '.
		'a.nom ASC, '.
		'a.prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$respos = $respos->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


if (!empty($_POST['maj']) &&
	!empty($_POST['nom']) &&
	isset($_POST['ecole_lyonnaise']) &&
	in_array($_POST['ecole_lyonnaise'], array('1', '0')) &&
	isset($_POST['adresse']) &&
	isset($_POST['code_postal']) && 
	isset($_POST['ville']) &&
	isset($_POST['email_ecole']) &&
	isset($_POST['telephone_ecole']) &&
	!empty($_POST['login']) &&
	isset($_POST['pass']) &&
	isset($_POST['pass_repetition']) &&
	isset($_POST['quota_total']) && (
		intval($_POST['quota_total']) > 0 ||
		empty($_POST['quota_total'])) && 
	isset($_POST['quota_logement']) && (
		intval($_POST['quota_logement']) > 0 ||
		empty($_POST['quota_logement'])) &&
	isset($_POST['quota_filles_logees']) && (
		intval($_POST['quota_filles_logees']) > 0 ||
		empty($_POST['quota_filles_logees'])) &&
	isset($_POST['quota_garcons_loges']) && (
		intval($_POST['quota_garcons_loges']) > 0 ||
		empty($_POST['quota_garcons_loges'])) && 
	isset($_POST['quota_sportif']) && (
		intval($_POST['quota_sportif']) > 0 ||
		empty($_POST['quota_sportif'])) &&
	isset($_POST['quota_pompom']) && (
		intval($_POST['quota_pompom']) > 0 ||
		empty($_POST['quota_pompom'])) &&
	isset($_POST['quota_pompom_nonsportif']) && (
		intval($_POST['quota_pompom_nonsportif']) > 0 ||
		empty($_POST['quota_pompom_nonsportif'])) &&
	isset($_POST['quota_fanfaron']) && (
		intval($_POST['quota_fanfaron']) > 0 ||
		empty($_POST['quota_fanfaron'])) &&
	isset($_POST['quota_fanfaron_nonsportif']) && (
		intval($_POST['quota_fanfaron_nonsportif']) > 0 ||
		empty($_POST['quota_fanfaron_nonsportif'])) &&
	isset($_POST['quota_cameraman']) && (
		intval($_POST['quota_cameraman']) > 0 ||
		empty($_POST['quota_cameraman'])) &&
	isset($_POST['quota_cameraman_nonsportif']) && (
		intval($_POST['quota_cameraman_nonsportif']) > 0 ||
		empty($_POST['quota_cameraman_nonsportif'])) &&
	isset($_POST['nom_respo']) &&
	isset($_POST['prenom_respo']) &&
	isset($_POST['email_respo']) &&
	isset($_POST['telephone_respo']) &&
	isset($_POST['nom_corespo']) &&
	isset($_POST['prenom_corespo']) &&
	isset($_POST['email_corespo']) &&
	isset($_POST['telephone_corespo']) &&
	isset($_POST['malus']) &&
	is_numeric($_POST['malus']) &&
	$_POST['malus'] >= 0) {
	
	$logement_on = !empty($_POST['quota_logement_on']);
	$filles_on = !empty($_POST['quota_filles_on']);
	$garcons_on = !empty($_POST['quota_garcons_on']);
	$pompom_on = !empty($_POST['quota_pompom_on']);
	$fanfaron_on = !empty($_POST['quota_fanfaron_on']);
	$cameraman_on = !empty($_POST['quota_cameraman_on']);
	$pompom_nonsportif_on = !empty($_POST['quota_pompom_nonsportif_on']);
	$fanfaron_nonsportif_on = !empty($_POST['quota_fanfaron_nonsportif_on']);
	$cameraman_nonsportif_on = !empty($_POST['quota_cameraman_nonsportif_on']);

	$logins = $pdo->query('SELECT '.
			'login '.
		'FROM ecoles '.
		'WHERE '.
			'id <> '.$ecole['id'])
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$logins = $logins->fetchAll(PDO::FETCH_ASSOC);


	if (in_array($_POST['login'], $logins))
		$erreur_maj = 'login';

	else if (!empty($_POST['pass']) && (
			empty($_POST['pass_repetition']) ||
			$_POST['pass'] != $_POST['pass_repetition']))
		$erreur_maj = 'pass';

	else if (
		$filles_on * $_POST['quota_garcons_loges'] + $garcons_on * $_POST['quota_filles_logees'] > $_POST['quota_total'] ||
		$logement_on && $filles_on * $_POST['quota_garcons_loges'] + $garcons_on * $_POST['quota_filles_logees'] > $_POST['quota_logement'] ||
		$_POST['quota_sportif'] > $_POST['quota_total'] ||
		$_POST['quota_sportif'] < $ecole['quota_sportif_view'] ||
		$pompom_on && $_POST['quota_pompom'] > $_POST['quota_total'] ||
		$fanfaron_on && $_POST['quota_fanfaron'] > $_POST['quota_total'] ||
		$cameraman_on && $_POST['quota_cameraman'] > $_POST['quota_total'] ||
		$pompom_on && $_POST['quota_pompom'] < $ecole['quota_pompom_view'] ||
		$fanfaron_on && $_POST['quota_fanfaron'] < $ecole['quota_fanfaron_view'] ||
		$cameraman_on && $_POST['quota_cameraman'] < $ecole['quota_cameraman_view'] ||
		$pompom_nonsportif_on && $_POST['quota_pompom_nonsportif'] > $_POST['quota_total'] ||
		$fanfaron_nonsportif_on && $_POST['quota_fanfaron_nonsportif'] > $_POST['quota_total'] ||
		$cameraman_nonsportif_on && $_POST['quota_cameraman_nonsportif'] > $_POST['quota_total'] ||
		$pompom_nonsportif_on && $pompom_on && $_POST['quota_pompom_nonsportif'] > $_POST['quota_pompom'] ||
		$fanfaron_nonsportif_on && $fanfaron_on && $_POST['quota_fanfaron_nonsportif'] > $_POST['quota_fanfaron'] ||
		$cameraman_nonsportif_on && $cameraman_on && $_POST['quota_cameraman_nonsportif'] > $_POST['quota_cameraman'] ||
		$pompom_nonsportif_on && $_POST['quota_pompom_nonsportif'] > ($_POST['quota_total'] - $_POST['quota_sportif']) ||
		$fanfaron_nonsportif_on && $_POST['quota_fanfaron_nonsportif'] > ($_POST['quota_total'] - $_POST['quota_sportif']) ||
		$cameraman_nonsportif_on && $_POST['quota_cameraman_nonsportif'] > ($_POST['quota_total'] - $_POST['quota_sportif']) ||
		$garcons_on && $_POST['quota_garcons_loges'] < $ecole['quota_garcons_loges_view'] ||
		$filles_on && $_POST['quota_filles_logees'] < $ecole['quota_filles_logees_view'] ||
		$_POST['quota_total'] < $ecole['quota_inscriptions'])
		$erreur_maj = 'quotas';

	else {
		$erreur_maj = false;

		$pdo->exec('UPDATE ecoles SET '.
				'nom = "'.secure($_POST['nom']).'", '.
				'ecole_lyonnaise = '.secure($_POST['ecole_lyonnaise']).', '.
				'adresse = "'.secure($_POST['adresse']).'", '.
				'code_postal = "'.secure($_POST['code_postal']).'", '.
				'ville = "'.secure($_POST['ville']).'", '.
				'email_ecole = "'.secure($_POST['email_ecole']).'", '.
				'telephone_ecole = "'.secure($_POST['telephone_ecole']).'", '.
				'login = "'.secure($_POST['login']).'", '.
				(!empty($_POST['pass']) ? 'pass = "'.hashPass($_POST['pass']).'", ' : null).
				'quota_total = '.(int) $_POST['quota_total'].', '.
				'quota_filles_on = '.($filles_on ? '1' : '0').', '.
				'quota_garcons_on = '.($garcons_on ? '1' : '0').', '.
				'quota_filles_logees = '.(int) $_POST['quota_filles_logees'].', '.
				'quota_garcons_loges = '.(int) $_POST['quota_garcons_loges'].', '.
				'quota_logement_on = '.($logement_on ? '1' : '0').', '.
				'quota_logement = '.(int) $_POST['quota_logement'].', '.
				'quota_sportif = '.(int) $_POST['quota_sportif'].', '.
				'quota_pompom_on = '.($pompom_on ? '1' : '0').', '.
				'quota_cameraman_on = '.($cameraman_on ? '1' : '0').', '.
				'quota_fanfaron_on = '.($fanfaron_on ? '1' : '0').', '.
				'quota_pompom_nonsportif_on = '.($pompom_nonsportif_on ? '1' : '0').', '.
				'quota_cameraman_nonsportif_on = '.($cameraman_nonsportif_on ? '1' : '0').', '.
				'quota_fanfaron_nonsportif_on = '.($fanfaron_nonsportif_on ? '1' : '0').', '.
				'quota_pompom = '.(int) $_POST['quota_pompom'].', '.
				'quota_cameraman = '.(int) $_POST['quota_cameraman'].', '.
				'quota_fanfaron = '.(int) $_POST['quota_fanfaron'].', '.
				'quota_pompom_nonsportif = '.(int) $_POST['quota_pompom_nonsportif'].', '.
				'quota_cameraman_nonsportif = '.(int) $_POST['quota_cameraman_nonsportif'].', '.
				'quota_fanfaron_nonsportif = '.(int) $_POST['quota_fanfaron_nonsportif'].', '.
				'nom_respo = "'.secure($_POST['nom_respo']).'", '.
				'prenom_respo = "'.secure($_POST['prenom_respo']).'", '.
				'email_respo = "'.secure($_POST['email_respo']).'", '.
				'telephone_respo = "'.secure($_POST['telephone_respo']).'", '.
				'nom_corespo = "'.secure($_POST['nom_corespo']).'", '.
				'prenom_corespo = "'.secure($_POST['prenom_corespo']).'", '.
				'email_corespo = "'.secure($_POST['email_corespo']).'", '.
				'telephone_corespo = "'.secure($_POST['telephone_corespo']).'", '.
				'malus = '.abs((float) $_POST['malus']).' '.
			'WHERE '.
				'id = '.$ecole['id']);
	}

	$_POST['id'] = $ecole['id'];

	$_POST['quota_logement_on'] = $logement_on;
	$_POST['quota_filles_on'] = $filles_on;
	$_POST['quota_garcons_on'] = $garcons_on;
	$_POST['quota_pompom_on'] = $pompom_on;
	$_POST['quota_fanfaron_on'] = $fanfaron_on;
	$_POST['quota_cameraman_on'] = $cameraman_on;
	$_POST['quota_pompom_nonsportif_on'] = $pompom_nonsportif_on;
	$_POST['quota_fanfaron_nonsportif_on'] = $fanfaron_nonsportif_on;
	$_POST['quota_cameraman_nonsportif_on'] = $cameraman_nonsportif_on;

	foreach ($_POST as $label => $value)
		if (!array_key_exists('login', $ecole))
			unset($_POST[$label]);

	foreach ($ecole as $label => $value)
		if (!isset($_POST[$label]))
			$_POST[$label] = $value;
	

	$ecole = $_POST;


} else if (!empty($_POST['maj']))
	$erreur_maj = 'champs';


if (!empty($_POST['change_etat']) &&
	!empty($_POST['etat']) &&
	in_array($_POST['etat'], array('fermee', 'ouverte', 'close', 'validee')) &&
	isset($_POST['respo']) && (
		in_array($_POST['respo'], array_keys($respos)) ||
		empty($_POST['respo']) &&
		$_POST['etat'] == 'fermee')) {

	$caution = !empty($_POST['caution']);
	$charte = !empty($_POST['charte']);
	$reglement = !empty($_POST['reglement']);


	$ecole['id_admin'] = !empty($_POST['respo']) ? $_POST['respo'] : null;
	$ecole['etat_inscription'] = $_POST['etat'];
	$ecole['caution_recue'] = !empty($_POST['caution']);
	$ecole['ri_signe'] = !empty($_POST['reglement']);
	$ecole['charte_acceptee'] = !empty($_POST['charte']);


	$pdo->exec('UPDATE ecoles SET '.
			'etat_inscription = "'.$_POST['etat'].'", '.
			(!empty($_POST['respo']) ? 'id_admin = '.$_POST['respo'].', ' : '').
			'caution_recue = '.(int) !empty($_POST['caution']).', '.
			'ri_signe = '.(int) !empty($_POST['reglement']).', '.
			'charte_acceptee = '.(int) !empty($_POST['charte']).' '.
		'WHERE '.
			'id = '.$ecole['id']);

	$edit_etat = true;
}

$sports = $pdo->query('SELECT '.
		's.id, '.
		's.sport, '.
		's.sexe, '.
		's.quota_max AS quota_total, '.
		'es.quota_max, '.
		'e.effectif, '.
		'SUM(autres.quota_max) AS quota_sum, '.
		'(SELECT '.
				'COUNT(DISTINCT sp.id_participant) '.
			'FROM sportifs AS sp WHERE '.
				'sp.id_sport = es.id_sport AND '.
				'sp.id_ecole = es.id_ecole) AS inscriptions '.
	'FROM ecoles_sports AS es '.
	'JOIN sports AS s ON '.
		's.id = es.id_sport '.
	'LEFT JOIN equipes AS e ON '.
		'e.id_ecole = es.id_ecole AND '.
		'e.id_sport = s.id '.
	'LEFT JOIN ecoles_sports AS autres ON '.
		'autres.id_ecole <> es.id_ecole AND '.
		'autres.id_sport = es.id_sport '.
	'WHERE '.
		'es.id_ecole = '.$ecole['id'].' '.
	'GROUP BY '.
		's.id '.
	'ORDER BY '.
		's.sport ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sports = $sports->fetchAll(PDO::FETCH_ASSOC);


$sports_ajout = $pdo->query('SELECT '.
		's.sport, '.
		's.id, '.
		's.sexe, '.
		's.quota_max AS quota_total, '.
		'SUM(es.quota_max) AS quota_sum '.
	'FROM sports AS s '.
	'LEFT JOIN ecoles_sports AS es ON '.
		'es.id_ecole <> es.id_ecole AND '.
		'es.id_sport = s.id '.
	'WHERE s.id NOT IN ('.
		'SELECT '.
			'id_sport '.
		'FROM ecoles_sports '.
		'WHERE '.
			'id_ecole = '.$ecole['id'].') '.
	'GROUP BY '.
		's.id '.
	'ORDER BY '.
		's.sport ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sports_ajout = $sports_ajout->fetchAll(PDO::FETCH_ASSOC);


if (!empty($_POST['maj_sports']) &&
	!empty($_POST['quotas']) &&
	!empty($_POST['sports']) &&
	is_array($_POST['sports']) &&
	is_array($_POST['quotas']) &&
	array_keys($_POST['quotas']) == array_keys($_POST['sports'])) {

	$sports_id = [];
	foreach ($sports as $sport) 
		$sports_id[$sport['id']] = $sport;

	$modif = $pdo->prepare('UPDATE ecoles_sports SET '.
			'quota_max = :quota_max '.
		'WHERE '.
			'id_sport = :id_sport AND '.
			'id_ecole = '.$ecole['id']);

	$suppr = $pdo->prepare('DELETE FROM ecoles_sports WHERE '.
		'id_sport = :id_sport AND '.
		'id_ecole = '.$ecole['id']);

	$erreur_quotas = false;
	foreach ($_POST['sports'] as $i => $id) {

		if (!empty($sports_id[$id]) && (
				intval($_POST['quotas'][$i]) ||
				$_POST['quotas'][$i] == 0) && (
				$_POST['quotas'][$i] >= (int) $sports_id[$id]['effectif'] ||
					$_POST['quotas'][$i] == 0 &&
					(int) $sports_id[$id]['inscriptions'] == 0)) {

			if ($_POST['quotas'][$i] == 0)
				$suppr->execute(array(
					':id_sport' => $id));

			else
				$modif->execute(array(
					':id_sport' => $id,
					':quota_max' => $_POST['quotas'][$i]));
		}

		else
			$erreur_quotas = true;
	}

}


if (!empty($_POST['add_sport']) &&
	!empty($_POST['sport']) &&
	!empty($_POST['quota-max']) &&
	intval($_POST['quota-max']) &&
	$_POST['quota-max'] > 0) {

	$sports_ajout_id = [];
	foreach ($sports_ajout as $sport_ajout) 
		$sports_ajout_id[] = $sport_ajout['id'];

	$erreur_quotas = isset($erreur_quotas) ?
		$erreur_quotas :
		!in_array($_POST['sport'], $sports_ajout_id);


	if (in_array($_POST['sport'], $sports_ajout_id))
		$pdo->exec('INSERT INTO ecoles_sports SET '.
			'id_ecole = '.$ecole['id'].', '.
			'id_sport = '.$_POST['sport'].', '.
			'quota_max = '.$_POST['quota-max']);

}


if (!empty($_POST['add_paiement']) &&
	isset($_POST['montant']) &&
	is_numeric($_POST['montant'])) {

	$pdo->exec('INSERT INTO paiements SET '.
		'id_ecole = '.$ecole['id'].', '.
		'date = NOW(), '.
		'montant = '.(float) $_POST['montant'].', '.
		'etat = "paye", '.
		'type = "manuel"');
	
	$ajout_paiement = true;
}



if (isset($erreur_quotas) &&
	!$erreur_quotas) {

	$sports = $pdo->query('SELECT '.
			's.id, '.
			's.sport, '.
			's.sexe, '.
			's.quota_max AS quota_total, '.
			'es.quota_max, '.
			'e.effectif, '.
			'SUM(autres.quota_max) AS quota_sum, '.
			'(SELECT '.
					'COUNT(DISTINCT sp.id_participant) '.
				'FROM sportifs AS sp WHERE '.
					'sp.id_sport = es.id_sport AND '.
					'sp.id_ecole = es.id_ecole) AS inscriptions '.
		'FROM ecoles_sports AS es '.
		'JOIN sports AS s ON '.
			's.id = es.id_sport '.
		'LEFT JOIN equipes AS e ON '.
			'e.id_ecole = es.id_ecole AND '.
			'e.id_sport = s.id '.
		
		'LEFT JOIN ecoles_sports AS autres ON '.
			'autres.id_ecole <> es.id_ecole AND '.
			'autres.id_sport = es.id_sport '.
		'WHERE '.
			'es.id_ecole = '.$ecole['id'].' '.
		'GROUP BY '.
			's.id '.
		'ORDER BY '.
			's.sport ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$sports = $sports->fetchAll(PDO::FETCH_ASSOC);


	$sports_ajout = $pdo->query('SELECT '.
			's.sport, '.
			's.id, '.
			's.sexe, '.
			's.quota_max AS quota_total, '.
			'SUM(es.quota_max) AS quota_sum '.
		'FROM sports AS s '.
		'LEFT JOIN ecoles_sports AS es ON '.
			'es.id_ecole <> es.id_ecole AND '.
			'es.id_sport = s.id '.
		'WHERE s.id NOT IN ('.
			'SELECT '.
				'id_sport '.
			'FROM ecoles_sports '.
			'WHERE '.
				'id_ecole = '.$ecole['id'].') '.
		'GROUP BY '.
			's.id '.
		'ORDER BY '.
			's.sport ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$sports_ajout = $sports_ajout->fetchAll(PDO::FETCH_ASSOC);

}




$participants = $pdo->query('SELECT '.
		'p.* '.
	'FROM participants AS p '.
	'WHERE '.
		'p.id_ecole = '.$ecole['id'].' '.
	'ORDER BY '.
		'p.nom ASC, '.
		'p.prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$participants = $participants->fetchAll(PDO::FETCH_ASSOC);


$paiements = $pdo->query('SELECT '.
		'p.* '.
	'FROM paiements AS p '.
	'WHERE '.
		'p.id_ecole = '.$ecole['id'].' '.
	'ORDER BY '.
		'p.date DESC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$paiements = $paiements->fetchAll(PDO::FETCH_ASSOC);


$montant_inscriptions = $pdo->query('SELECT '.
		'SUM(tarif) AS montant '.
	'FROM participants AS p '.
	'LEFT JOIN tarifs AS t ON '.
		'p.id_tarif = t.id '.
	'WHERE '.
		'p.id_ecole = '.$ecole['id'])
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$montant_inscriptions = $montant_inscriptions->fetch(PDO::FETCH_ASSOC);


$montant_recharges = $pdo->query('SELECT '.
		'SUM(montant) AS montant '.
	'FROM participants AS p '.
	'LEFT JOIN recharges AS r ON '.
		'p.id_recharge = r.id '.
	'WHERE '.
		'p.id_ecole = '.$ecole['id'])
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$montant_recharges = $montant_recharges->fetch(PDO::FETCH_ASSOC);


$montant_paye = $pdo->query('SELECT '.
		'SUM(montant) AS montant '.
	'FROM paiements '.
	'WHERE '.
		'id_ecole = '.$ecole['id'].' AND '.
		'etat = "paye"')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$montant_paye = $montant_paye->fetch(PDO::FETCH_ASSOC);


$inscriptions_enretard = $pdo->query('SELECT '.
		'COUNT(p.id) AS nbretards, '.
		'SUM(tarif) AS montant '.
	'FROM participants AS p '.
	'LEFT JOIN tarifs AS t ON '.
		't.id = p.id_tarif '.
	'WHERE '.
		'id_ecole = '.$ecole['id'].' AND '.
		'date_inscription > "'.APP_DATE_MALUS.'"')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$inscriptions_enretard = $inscriptions_enretard->fetch(PDO::FETCH_ASSOC);



//Inclusion du bon fichier de template
require DIR.'templates/admin/ecoles/edition.php';
