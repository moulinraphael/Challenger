<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_sans_sport_ecoles.php **/
/* Liste des sportifs sans sport groupés par école *********/
/* *********************************************************/
/* Dernière modification : le 20/01/15 *********************/
/* *********************************************************/


$sans_sport = $pdo->query('SELECT '.
		'e.id AS eid, '.
		'p.id, '.
		'p.nom, '.
		'p.prenom, '.
		'p.sexe, '.
		'p.telephone, '.
		'p.licence, '.
		'e.nom AS enom '.
	'FROM ecoles AS e '.
	'LEFT JOIN participants AS p ON '.
		'e.id = p.id_ecole AND '.
		'p.sportif = 1 AND '.
		'p.id NOT IN (SELECT '.
				's.id_participant '.
			'FROM sportifs AS s WHERE '.
				's.id_ecole = e.id) '.
	'ORDER BY '.
		'e.nom ASC, '.
		'p.nom ASC, '.
		'p.prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sans_sport = $sans_sport->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);


//Labels pour le XLSX
$labels = [
	'Nom' => 'nom',
	'Prénom' => 'prenom',
	'Sexe' => 'sexe',
	'Licence' => 'licence',
	'Téléphone' => 'telephone',
];


//Téléchargement du fichier XLSX concerné
if (!empty($_GET['excel']) &&
	intval($_GET['excel']) &&
	in_array($_GET['excel'], array_keys($sans_sport))) {

	$titre = 'Liste des sportifs sans sport ('.unsecure($sans_sport[$_GET['excel']][0]['enom']).')';
	$fichier = 'liste_sans_sport_ecole_'.onlyLetters(unsecure($sans_sport[$_GET['excel']][0]['enom']));
	$items = $sans_sport[$_GET['excel']];
	exportXLSX($items, $fichier, $titre, $labels);

}


else if (isset($_GET['excel'])) {

	$fichier = 'liste_sans_sport_ecoles';
	$items = $titres = $feuilles[] = [];
	
	$i = 0;
	foreach ($sans_sport as $sans_sport_ecole) {
		$titres[$i] = 'Liste des sportifs sans sport ('.unsecure($sans_sport_ecole[0]['enom']).')';
		$feuilles[$i] = unsecure($sans_sport_ecole[0]['enom']);
		$items[$i] = $sans_sport_ecole;
		$i++;
	}
	
	exportXLSXGroupe($items, $fichier, $feuilles, $titres, $labels);

}


//Inclusion du bon fichier de template
require DIR.'templates/admin/competition/sans_sport_ecoles.php';
