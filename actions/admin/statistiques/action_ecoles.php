<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/statistiques/action_ecoles.php ************/
/* Liste des Ecoles ****************************************/
/* *********************************************************/
/* Dernière modification : le 16/02/15 *********************/
/* *********************************************************/


$ecoles = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom, '.
		'e.login, '.
		'e.caution_recue, '.
		'e.ri_signe, '.
		'e.charte_acceptee, '.
		'e.connexion, '.
		'e.quota_total, '.
		'e.etat_inscription, '.
		'(SELECT COUNT(p1.id) FROM participants AS p1 WHERE p1.id_ecole = e.id) AS quota_inscriptions '.
	'FROM ecoles AS e '.
	'ORDER BY e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$ecoles = $ecoles->fetchAll(PDO::FETCH_ASSOC);


//Inclusion du bon fichier de template
require DIR.'templates/admin/statistiques/ecoles.php';
