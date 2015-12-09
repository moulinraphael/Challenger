<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/public/login_admin.php **************************/
/* Gére la connexion pour l'administration *****************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


if (!empty($_SESSION['admin']))
	die(header('location:'.url('admin/accueil', false, false)));


require DIR.'includes/_ecl/CAS.php';
phpCAS::client(CAS_VERSION_2_0, CONFIG_CAS_HOST, CONFIG_CAS_PORT, CONFIG_CAS_CONTEXT);
phpCAS::setNoCasServerValidation();


if (!phpCAS::checkAuthentication())   
	phpCAS::forceAuthentication(); 

else {

	$cas = phpCAS::getUser();
	$user = $pdo->query('SELECT '.
			'id '.
		'FROM admins WHERE '.
			'auth_type = "cas" AND '.
			'login = "'.secure($cas).'"') or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));

	}

	$user = $user->fetch(PDO::FETCH_ASSOC) ;

	if (!empty($user)) {

		$_SESSION['admin'] = [
			'start' => time(),
			'last' => time(),
			'auth_type' => 'cas',
			'login' => secure($cas),
			'user' => $user['id'],
		];
		die(header('location:'.url('admin/accueil', false, false)));
	}

	else
		$error = true;
}


//Inclusion du bon fichier de template
require DIR.'templates/public/login_admin.php';