<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/module_ecoles.php *************************/
/* Supervision du module des Ecoles ************************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//Liste des actions possibles
$actionsModule = [
	'accueil' 			=> 'Message',
	'liste'				=> 'Liste des Ecoles',
	'tarification'		=> 'Tarification',
	'visibilite'		=> 'Visibilité Tarifs',
	'recharges'			=> 'Recharges',
];


//On récupère l'action désirée par l'utilisateur
$action = !empty($args[2][0]) ? $args[2][0] : 'liste';
if (!in_array($action, array_keys($actionsModule)) &&
	!intval($action))
	die(require DIR.'templates/_error.php');


//On recupère l'école si une édition est demandée
if (intval($action)) {

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
			'e.id = '.(int) $action)
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$ecole = $ecole->fetch(PDO::FETCH_ASSOC);

	if (empty($ecole['id']))
		die(require DIR.'templates/_error.php');

	die(require DIR.'actions/admin/ecoles/action_edition.php');
}

//On insére le module concerné
require DIR.'actions/admin/ecoles/action_'.$action.'.php';
