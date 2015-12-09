<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/logout.php ********************************/
/* Gére la déconnexion de l'administration *****************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


$cas = !empty($_SESSION['admin']) &&
	$_SESSION['admin']['auth_type'] ==  'cas';

unset($_SESSION['admin']);


if ($cas) {

	require DIR.'includes/_ecl/CAS.php';
	phpCAS::client(CAS_VERSION_2_0, CONFIG_CAS_HOST, CONFIG_CAS_PORT, CONFIG_CAS_CONTEXT);
	phpCAS::setNoCasServerValidation();

	phpCAS::logoutWithRedirectService(url('', true, false));

}

//Redirection vers l'accueil
die(header('location:'.url('', false, false)));
