<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/statistiques/action_paiements.php **********/
/* Liste des paiements *************************************/
/* *********************************************************/
/* Dernière modification : le 24/01/15 *********************/
/* *********************************************************/



$paiements = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom, '.
		'e.ecole_lyonnaise, '.
		'pa.id AS paid, '.
		'pa.date, '.
		'pa.montant, '.
		'pa.etat, '.
		'pa.type '.
	'FROM ecoles AS e '.
	'LEFT JOIN paiements AS pa ON '.
		'pa.id_ecole = e.id '.
	'ORDER BY '.
		'e.nom ASC, '.
		'pa.date DESC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$paiements = $paiements->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);



//Inclusion du bon fichier de template
require DIR.'templates/admin/statistiques/paiements.php';
