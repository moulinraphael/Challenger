<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/vp/logout.php ***********************************/
/* Gére la déconnexion pour le VP **************************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//On ferme tous les modules
session_name('vp');
unset($_SESSION['vp']);


//Redirection vers l'accueil
die(header('location:'.url('', false, false)));
