<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/ecoles/logout.php *******************************/
/* Gére la déconnexion pour les écoles *********************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//On ferme tous les modules
unset($_SESSION['ecole']);


//Redirection vers l'accueil
die(header('location:'.url('', false, false)));
