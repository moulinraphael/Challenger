<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_fanfarons_ecoles.php ***/
/* Liste des fanfarons *************************************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/



$fanfarons = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom, '.
		'e.ecole_lyonnaise, '.
		'e.quota_fanfaron, '.
		'p.nom AS pnom, '.
		'p.prenom AS pprenom, '.
		'p.sexe AS psexe, '.
		'p.telephone AS ptelephone, '.
		'p.id AS pid '.
	'FROM ecoles AS e '.
	'LEFT JOIN participants AS p ON '.
		'p.fanfaron = 1 AND '.
		'p.id_ecole = e.id '.
	'ORDER BY '.
		'e.nom ASC, '.
		'p.nom ASC, '.
		'p.prenom ASC ')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$fanfarons = $fanfarons->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);


//Labels pour le XLSX
$labels = [
	'Nom' => 'pnom',
	'Prénom' => 'pprenom',
	'Sexe' => 'psexe',
	'Téléphone' => 'ptelephone',
];

//Téléchargement du fichier XLSX concerné
if (!empty($_GET['excel']) &&
	intval($_GET['excel']) &&
	in_array($_GET['excel'], array_keys($fanfarons))) {

	$titre = 'Liste des fanfarons ('.unsecure($fanfarons[$_GET['excel']][0]['nom']).')';
	$fichier = 'liste_fanfarons_ecole_'.onlyLetters(unsecure($fanfarons[$_GET['excel']][0]['nom']));
	$items = $fanfarons[$_GET['excel']];
	exportXLSX($items, $fichier, $titre, $labels);

}

else if (isset($_GET['excel'])) {

	$fichier = 'liste_fanfarons_ecoles';
	$items = $titres = $feuilles[] = [];
	
	$i = 0;
	foreach ($fanfarons as $fanfarons_ecole) {
		$titres[$i] = 'Liste des fanfarons ('.unsecure($fanfarons_ecole[0]['nom']).')';
		$feuilles[$i] = unsecure($fanfarons_ecole[0]['nom']);
		$items[$i] = $fanfarons_ecole;
		$i++;
	}
	
	exportXLSXGroupe($items, $fichier, $feuilles, $titres, $labels);

}


//Inclusion du bon fichier de template
require DIR.'templates/admin/competition/fanfarons_ecoles.php';
