<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/module_competition.php ********************/
/* Supervision du module de Compétition ********************/
/* *********************************************************/
/* Dernière modification : le 23/02/15 *********************/
/* *********************************************************/


//Liste des actions possibles
$actionsModule = [
	'sports' 					=> 'Sports',
	'participants'				=> 'Participants',
	'participants_ecoles'		=> 'Participants / Ecole',
	'sportifs'					=> 'Sportifs',
	'sportifs_sports'			=> 'Sportifs / Sport',
	'sportifs_sports_groupes'	=> 'Sportifs / Sport G',
	'sportifs_ecoles'			=> 'Sportifs / Ecole',
	'sportifs_ecoles_groupes'	=> 'Sportifs / Ecole G',
	'sans_sport'				=> 'Sans Sport',
	'sans_sport_ecoles'			=> 'Sans Sport / Ecole',
	'pompoms'					=> 'Pompoms',
	'pompoms_ecoles'			=> 'Pompoms / Ecole',
	'fanfarons'					=> 'Fanfarons',
	'fanfarons_ecoles'			=> 'Fanfarons / Ecole',
	'capitaines'				=> 'Capitaines',
	'capitaines_sports'			=> 'Capitaines / Sport',
	'capitaines_ecoles'			=> 'Capitaines / Ecole',
];


//On récupère l'action désirée par l'utilisateur
$action = !empty($args[2][0]) ? $args[2][0] : 'sports';
if (!in_array($action, array_keys($actionsModule)))
	die(require DIR.'templates/_error.php');


//On insére le module concerné
require DIR.'actions/admin/competition/action_'.$action.'.php';
