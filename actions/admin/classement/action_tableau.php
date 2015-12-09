<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/classement/action_tableau.php *************/
/* Tableau des classements *********************************/
/* *********************************************************/
/* Dernière modification : le 17/02/15 *********************/
/* *********************************************************/


$ecoles = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom '.
	'FROM ecoles AS e '.
	'ORDER BY e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$ecoles = $ecoles->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$sports = $pdo->query('SELECT '.
		's.id, '.
		'(SELECT COUNT(es.id_ecole) FROM ecoles_sports AS es WHERE es.id_sport = s.id) AS nb_equipes, '.
		'(SELECT COUNT(sp.id_participant) FROM sportifs AS sp WHERE sp.id_sport = s.id) AS nb_sportifs, '.
		's.sport, '.
		's.sexe '.
	'FROM sports AS s '.
	'WHERE ID NOT IN (SELECT id_sport FROM classements) '.
	'ORDER BY '.
		'sport ASC, '.
		'sexe ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sports = $sports->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$classements = $pdo->query('SELECT '.
		'c.*, '.
		's.sport, '.
		's.sexe '.
	'FROM sports AS s '.
	'JOIN classements AS c ON '.
		's.id = c.id_sport '.
	'ORDER BY '.
		's.sport ASC, '.
		's.sexe ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$classements = $classements->fetchAll(PDO::FETCH_ASSOC);

foreach ($ecoles as $eid => $ecole) 
	$ecoles[$eid]['points'] = 0;


foreach ($classements as $classement) {
	
	$ecoles[$classement['id_ecole_1']]['points'] += APP_POINTS_1ER * $classement['coeff'];
	$ecoles[$classement['id_ecole_2']]['points'] += ($classement['ex_12'] ? APP_POINTS_1ER : APP_POINTS_2E) * $classement['coeff'];
	$ecoles[$classement['id_ecole_3']]['points'] += 
		($classement['ex_12'] ? ($classement['ex_23'] ? APP_POINTS_1ER : APP_POINTS_3E) :  ($classement['ex_23'] ? APP_POINTS_2E : APP_POINTS_3E)) * $classement['coeff'];

	if ($classement['ex_3'])
		$ecoles[$classement['id_ecole_3ex']]['points'] += ($classement['ex_12'] && $classement['ex_23'] ? APP_POINTS_1ER : ($classement['ex_23'] ? APP_POINTS_2E : APP_POINTS_3E)) * $classement['coeff'];

}

function triPoints ($ecoleA, $ecoleB) {
	if ($ecoleA['points'] == $ecoleB['points']) return 0;
	return $ecoleA['points'] > $ecoleB['points'] ? -1 : 1;
}

uasort($ecoles, 'triPoints');


//Inclusion du bon fichier de template
require DIR.'templates/admin/classement/tableau.php';
