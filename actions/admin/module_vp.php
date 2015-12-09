<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/module_vp.php *****************************/
/* Supervision du module de VP *****************************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//Liste des actions possibles
$actionsModule = [
	'accueil' 	=> 'Accueil',
	'liste'		=> 'Liste des VP',
];


//On récupère l'action désirée par l'utilisateur
$action = !empty($args[2][0]) ? $args[2][0] : 'accueil';
if (!in_array($action, array_keys($actionsModule)))
	die(require DIR.'templates/_error.php');


//On insére le module concerné
require DIR.'actions/admin/vp/action_'.$action.'.php';
