<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/statistiques/action_tarifs.php ************/
/* Liste des Tarifs ****************************************/
/* *********************************************************/
/* Dernière modification : le 23/01/15 *********************/
/* *********************************************************/


$tarifs_ecoles = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom, '.
		'ecole_lyonnaise, '.
		'COUNT(p.id) AS pnb, '.
		'te.id_tarif '.
	'FROM ecoles AS e '.
	'LEFT JOIN tarifs_ecoles AS te ON '.
		'te.id_ecole = e.id '.
	'LEFT JOIN participants AS p ON '.
		'p.id_tarif = te.id_tarif AND '.
		'p.id_ecole = e.id '.
	'GROUP BY '.
		'e.id, te.id_tarif '.
	'ORDER BY '.
		'ecole_lyonnaise DESC, '.
		'e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$tarifs_ecoles = $tarifs_ecoles->fetchAll(PDO::FETCH_ASSOC);


$tarifs = $pdo->query('SELECT '.
		'id, '.
		'nom, '.
		'type, '.
		'tarif, '.
		'ecole_lyonnaise '.
	'FROM tarifs '.
	'ORDER BY '.
		'type ASC, '.
		'ordre ASC, '.
		'nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$tarifs = $tarifs->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$ecoles = [];
foreach ($tarifs_ecoles as $tarif) {
	if (!isset($ecoles[$tarif['id']]))
		$ecoles[$tarif['id']] = array_merge($tarif, array('tarifs' => []));

	$ecoles[$tarif['id']]['tarifs'][$tarif['id_tarif']] = $tarif['pnb'];
}


//Inclusion du bon fichier de template
require DIR.'templates/admin/statistiques/tarifs.php';
