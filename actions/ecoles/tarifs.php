<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/ecoles/tarifs.php *******************************/
/* Détail des tarifs ***************************************/
/* *********************************************************/
/* Dernière modification : le 09/12/14 *********************/
/* *********************************************************/


if (empty($_SESSION['ecole']) ||
	empty($_SESSION['ecole']['user']))
	die(header('location:'.url('accueil', false, false)));


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
	$ecole['etat_inscription'] != 'ouverte')
	die(require DIR.'templates/_error.php');


$tarifs = $pdo->query('SELECT '.
		't.type AS _group, '.
		't.type, '.
		't.id, '.
		't.tarif, '.
		't.nom, '.
		't.description, '.
		't.id_sport_special, '.
		's.sport, '.
		's.sexe, '.
		't.logement, '.
		't.for_pompom, '.
		't.for_cameraman, '.
		't.for_fanfaron '.
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


//Inclusion du bon fichier de template
require DIR.'templates/ecoles/tarifs.php';
