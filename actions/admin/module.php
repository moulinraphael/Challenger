<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/module.php ********************************/
/* Redirection suivant les modules de l'administration *****/
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


//L'utilisateur peut-il accéder dans le module concerné
$module = $args[1][0];
if (empty($_SESSION['admin']['privileges']) ||
	!in_array($module, $_SESSION['admin']['privileges']))
	die(require DIR.'templates/_error.php');


//On insére le module concerné
require DIR.'actions/admin/module_'.$module.'.php';
