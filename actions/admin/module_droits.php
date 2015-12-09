<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/module_droits.php *************************/
/* Supervision du module des droits ************************/
/* *********************************************************/
/* Dernière modification : le 24/11/14 *********************/
/* *********************************************************/


//Liste des actions possibles
$actionsModule = [
	'liste' 	=> 'Liste des organisateurs',
	'admins' 	=> 'Liste des admins',
	'droits'	=> 'Gestion des droits'
];


//On récupère l'action désirée par l'utilisateur
$action = !empty($args[2][0]) ? $args[2][0] : 'liste';
if (!in_array($action, array_keys($actionsModule)) &&
	!intval($action))
	die(require DIR.'templates/_error.php');


//On insére le module concerné
require DIR.'actions/admin/droits/action_'.$action.'.php';
