<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/ecoles/recapitulatif.php ************************/
/* Récap de l'école ****************************************/
/* *********************************************************/
/* Dernière modification : le 16/02/14 *********************/
/* *********************************************************/


if (empty($_SESSION['ecole']) ||
	empty($_SESSION['ecole']['user']))
	die(header('location:'.url('accueil', false, false)));


$ecole = $pdo->query('SELECT '.
		'e.*, '.
		'a.login AS alogin, '.
		'a.nom AS anom, '.
		'a.prenom AS aprenom, '.
		'a.email AS aemail, '.
		'a.poste AS aposte, '.
		'a.auth_type AS aauth_type, '.
		'a.telephone AS atelephone, '.
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
	'LEFT JOIN admins AS a ON '.
		'a.id = e.id_admin '.
	'WHERE '.
		'e.id = '.(int) $_SESSION['ecole']['user'])
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$ecole = $ecole->fetch(PDO::FETCH_ASSOC);


if (empty($ecole) ||
	$ecole['etat_inscription'] == 'fermee')
	die(require DIR.'templates/_error.php');


$sans_sport = $pdo->query('SELECT '.
		'id, '.
		'nom, '.
		'prenom, '.
		'sexe, '.
		'licence '.
	'FROM participants AS p '.
	'WHERE '.
		'sportif = 1 AND '.
		'id_ecole = '.$_SESSION['ecole']['user'].' AND '.
		'id NOT IN (SELECT '.
				'id_participant '.
			'FROM sportifs WHERE '.
				'id_ecole = '.$_SESSION['ecole']['user'].') '.
	'ORDER BY '.
		'nom ASC, '.
		'prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sans_sport = $sans_sport->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$participants = $pdo->query('SELECT '.
		'p.id, '.
		'p.nom, '.
		'p.prenom, '.
		'p.sexe, '.
		't.nom AS tarif, '.
		'r.nom AS recharge, '.
		't.logement, '.
		'p.logeur, '.
		'r.montant + t.tarif AS montant, '.
		'CASE WHEN p.date_inscription > "'.APP_DATE_MALUS.'" THEN 1 ELSE 0 END AS retard '.
	'FROM participants AS p '.
	'JOIN tarifs AS t ON '.
		't.id = p.id_tarif '.
	'JOIN recharges AS r ON '.
		'r.id = p.id_recharge '.
	'WHERE '.
		'id_ecole = '.$_SESSION['ecole']['user'].' '.
	'ORDER BY '.
		'nom ASC, '.
		'prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$participants = $participants->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$pompoms = $pdo->query('SELECT '.
		'id, '.
		'nom, '.
		'prenom, '.
		'sexe '.
	'FROM participants AS p '.
	'WHERE '.
		'pompom = 1 AND '.
		'id_ecole = '.$_SESSION['ecole']['user'].' '.
	'ORDER BY '.
		'nom ASC, '.
		'prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$pompoms = $pompoms->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$fanfarons = $pdo->query('SELECT '.
		'id, '.
		'nom, '.
		'prenom, '.
		'sexe '.
	'FROM participants AS p '.
	'WHERE '.
		'fanfaron = 1 AND '.
		'id_ecole = '.$_SESSION['ecole']['user'].' '.
	'ORDER BY '.
		'nom ASC, '.
		'prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$fanfarons = $fanfarons->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$equipes_sportifs = $pdo->query('SELECT '.
		's.id, '.
		'es.quota_max, '.
		's.sport, '.
		's.sexe, '.
		's.id_respo, '.
		'e.effectif, '.
		'e.id_capitaine AS cid, '.
		'p.nom AS pnom, '.
		'p.prenom AS pprenom, '.
		'p.sexe AS psexe, '.
		'p.licence AS plicence, '.
		'p.id AS pid, '.
		'a.login AS alogin, '.
		'a.nom AS anom, '.
		'a.prenom AS aprenom, '.
		'a.email AS aemail, '.
		'a.poste AS aposte, '.
		'a.telephone AS atelephone '.
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
	'LEFT JOIN admins AS a ON '.
		'a.id = s.id_respo '.
	'WHERE '.
		'e.id_ecole = '.$_SESSION['ecole']['user'].' '.
	'ORDER BY '.
		'sport ASC, '.
		'sexe ASC, '.
		'p.nom ASC, '.
		'p.prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$equipes_sportifs = $equipes_sportifs->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);


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
require DIR.'templates/ecoles/recapitulatif.php';
