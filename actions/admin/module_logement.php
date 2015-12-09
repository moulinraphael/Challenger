<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/module_logement.php ***********************/
/* Supervision du module de Logement ***********************/
/* *********************************************************/
/* Dernière modification : le 17/02/15 *********************/
/* *********************************************************/


//Liste des actions possibles
$actionsModule = [
	'recapitulatif'	=> 'Récapitulatif',
	'recensement'	=> 'Recensement',
	'chambres'		=> 'Chambres',
	'filles'		=> 'Filles',
];


//On récupère l'action désirée par l'utilisateur
$action = !empty($args[2][0]) ? $args[2][0] : 'recapitulatif';
if (!in_array($action, array_keys($actionsModule)) &&
	!in_array($action, str_split('UVTXABC')) &&
	!preg_match('`^_[UVTXABC]$`', $action))
	die(require DIR.'templates/_error.php');


//On insére le module concerné
$action = in_array($action, str_split('UVTXABC')) ? 'recensement_batiment' : $action;
$action = preg_match('`^_[UVTXABC]$`', $action) ? 'chambres_batiment' : $action;

require DIR.'actions/admin/logement/action_'.$action.'.php';
