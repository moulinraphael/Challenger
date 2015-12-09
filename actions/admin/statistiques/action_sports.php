<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/statistiques/action_sports.php ************/
/* Liste des Sports ****************************************/
/* *********************************************************/
/* Dernière modification : le 23/01/15 *********************/
/* *********************************************************/


$sports_ecoles = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom, '.
		'ecole_lyonnaise, '.
		'COUNT(sp.id_participant) AS spnb, '.
		'es.quota_max, '.
		'es.id_sport, '.
		'eq.effectif '.
	'FROM ecoles AS e '.
	'LEFT JOIN ecoles_sports AS es ON '.
		'es.id_ecole = e.id '.
	'LEFT JOIN equipes AS eq ON '.
		'eq.id_ecole = e.id AND '.
		'eq.id_sport = es.id_sport '.
	'LEFT JOIN sportifs AS sp ON '.
		'sp.id_sport = es.id_sport AND '.
		'sp.id_ecole = e.id '.
	'GROUP BY '.
		'e.id, es.id_sport '.
	'ORDER BY '.
		'e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sports_ecoles = $sports_ecoles->fetchAll(PDO::FETCH_ASSOC);


$sports = $pdo->query('SELECT '.
		'id, '.
		'sport, '.
		'sexe '.
	'FROM sports '.
	'ORDER BY '.
		'sport ASC, '.
		'sexe ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sports = $sports->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$ecoles = [];
foreach ($sports_ecoles as $sport) {
	if (!isset($ecoles[$sport['id']]))
		$ecoles[$sport['id']] = array_merge($sport, array('tarifs' => []));

	$ecoles[$sport['id']]['sports'][$sport['id_sport']] = $sport['spnb'];
}


//Inclusion du bon fichier de template
require DIR.'templates/admin/statistiques/sports.php';
