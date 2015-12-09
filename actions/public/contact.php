<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/public/contact.php ******************************/
/* Page de contact *****************************************/
/* *********************************************************/
/* Dernière modification : le 12/12/14 *********************/
/* *********************************************************/


$contacts = $pdo->query('SELECT '.
		'id, '.
		'nom, '.
		'login, '.
		'prenom, '.
		'email, '.
		'telephone, '.
		'poste, '.
		'auth_type '.
	'FROM admins AS a '.
	'WHERE '.
		'contact = 1 '.
	'ORDER BY '.
		'nom ASC, '.
		'prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$contacts = $contacts->fetchAll(PDO::FETCH_ASSOC);


//Inclusion du bon fichier de template
require DIR.'templates/public/contact.php';