<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/statistiques/action_frais.php *************/
/* Liste des Frais *****************************************/
/* *********************************************************/
/* Dernière modification : le 16/02/15 *********************/
/* *********************************************************/


$ecoles = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom, '.
		'e.malus, '.
		'(SELECT SUM(r.montant) FROM participants AS p0 JOIN recharges AS r ON r.id = p0.id_recharge WHERE p0.id_ecole = e.id) AS sum_recharges, '.
		'(SELECT SUM(t1.tarif) FROM participants AS p3 JOIN tarifs AS t1 ON t1.id = p3.id_tarif WHERE p3.id_ecole = e.id) AS sum_tarifs, '.
		'(SELECT SUM(t2.tarif) FROM participants AS p4 JOIN tarifs AS t2 ON t2.id = p4.id_tarif WHERE p4.id_ecole = e.id AND p4.date_inscription > "'.APP_DATE_MALUS.'") AS sum_retards, '.
		'(SELECT SUM(pa.montant) FROM paiements AS pa WHERE pa.id_ecole = e.id) AS sum_paiements, '.
		'(SELECT COUNT(p1.id) FROM participants AS p1 WHERE p1.id_ecole = e.id) AS quota_inscriptions, '.
		'(SELECT COUNT(p2.id) FROM participants AS p2 WHERE p2.id_ecole = e.id AND p2.date_inscription > "'.APP_DATE_MALUS.'") AS quota_retards '.
	'FROM ecoles AS e '.
	'ORDER BY e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$ecoles = $ecoles->fetchAll(PDO::FETCH_ASSOC);


//Inclusion du bon fichier de template
require DIR.'templates/admin/statistiques/frais.php';
