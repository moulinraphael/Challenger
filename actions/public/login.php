<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/public/login.php ********************************/
/* Gére la connexion dans les différents modules ***********/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//On détermine les modules actifs
$modules_actifs = 0;

$modules_actifs += $admin_actif = !empty($_SESSION['admin']);
$modules_actifs += $ecole_actif = !empty($_SESSION['ecole']);
$modules_actifs += $vp_actif = !empty($_SESSION['vp']);


//L'utilisateur n'est connecté que sur un seul module
if ($modules_actifs == 1)
	die(header('location:'.url($admin_actif ? 'admin' : ($ecole_actif ? 'ecole' : 'vp'), false, false)));


//L'utilisateur n'est connecté à aucun module
if ($modules_actifs == 0)
	die(header('location:'.url('ecole', false, false)));


//Inclusion du bon fichier de template
require DIR.'templates/public/login.php';