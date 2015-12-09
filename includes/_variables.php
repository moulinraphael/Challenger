<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* includes/_variables.php *********************************/
/* Mise en place de certaines données dynamiques ***********/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//Mode Debug (Les fichiers commencants par .ht sont innacessibles)
error_reporting(-1);
ini_set('display_errors', DEBUG_ACTIVE);
ini_set('display_startup_errors', DEBUG_ACTIVE);
ini_set('log_errors', 1);
ini_set('error_log', DIR.'.hterrlog');


//Ouvertue de la base de données
$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
