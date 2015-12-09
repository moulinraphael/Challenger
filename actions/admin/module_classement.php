<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/module_configurations.php *****************/
/* Supervision du module des constantes ********************/
/* *********************************************************/
/* Dernière modification : le 17/02/14 *********************/
/* *********************************************************/


//Liste des actions possibles
$actionsModule = [
	'edition' 	=> 'Edition',
	'tableau'	=> 'Tableau',
];


//On récupère l'action désirée par l'utilisateur
$action = !empty($args[2][0]) ? $args[2][0] : 'edition';
if (!in_array($action, array_keys($actionsModule)))
	die(require DIR.'templates/_error.php');


//On insére le module concerné
require DIR.'actions/admin/classement/action_'.$action.'.php';
