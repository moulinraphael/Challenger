<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/module_statistiques.php *******************/
/* Supervision du module des stats *************************/
/* *********************************************************/
/* Dernière modification : le 23/01/15 *********************/
/* *********************************************************/


//Liste des actions possibles
$actionsModule = [
	'ecoles' 	=> 'Ecoles',
	'sports' 	=> 'Sports',
	'paiements' => 'Paiements',
	'frais'		=> 'Frais',
	'tarifs' 	=> 'Tarifs',
];


//On récupère l'action désirée par l'utilisateur
$action = !empty($args[2][0]) ? $args[2][0] : 'ecoles';
if (!in_array($action, array_keys($actionsModule)))
	die(require DIR.'templates/_error.php');


//On insére le module concerné
require DIR.'actions/admin/statistiques/action_'.$action.'.php';
