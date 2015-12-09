<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/public/logout.php *******************************/
/* Gére la déconnexion dans les différents modules *********/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//On ferme tous les modules
unset($_SESSION['admin']);
unset($_SESSION['ecole']);
unset($_SESSION['vp']);


//Redirection vers l'accueil
die(header('location:'.url('', false, false)));
