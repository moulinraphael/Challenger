<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/accueil.php *******************************/
/* Accueil de l'administration *****************************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


require DIR.'includes/_ecl/CAS.php';
phpCAS::client(CAS_VERSION_2_0, CONFIG_CAS_HOST, CONFIG_CAS_PORT, CONFIG_CAS_CONTEXT);
phpCAS::setNoCasServerValidation();

if (phpCAS::isAuthenticated())
	$cas = phpCAS::getUser();


if (empty($_SESSION['admin']) ||
	$_SESSION['admin']['auth_type'] == 'cas' &&
	!empty($cas) &&
	$cas != $_SESSION['admin']['login']) {
	unset($_SESSION['admin']);
	die(header('location:'.url('admin', false, false)));
}


//Inclusion du bon fichier de template
require DIR.'templates/admin/accueil.php';