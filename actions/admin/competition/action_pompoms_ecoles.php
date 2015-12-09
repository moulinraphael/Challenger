<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_pompoms_ecoles.php *****/
/* Liste des pompoms ***************************************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/



$pompoms = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom, '.
		'e.ecole_lyonnaise, '.
		'e.quota_pompom, '.
		'p.nom AS pnom, '.
		'p.prenom AS pprenom, '.
		'p.sexe AS psexe, '.
		'p.telephone AS ptelephone, '.
		'p.id AS pid '.
	'FROM ecoles AS e '.
	'LEFT JOIN participants AS p ON '.
		'p.pompom = 1 AND '.
		'p.id_ecole = e.id '.
	'ORDER BY '.
		'e.nom ASC, '.
		'p.nom ASC, '.
		'p.prenom ASC ')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$pompoms = $pompoms->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);


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
	in_array($_GET['excel'], array_keys($pompoms))) {

	$titre = 'Liste des pompoms ('.unsecure($pompoms[$_GET['excel']][0]['nom']).')';
	$fichier = 'liste_pompoms_ecole_'.onlyLetters(unsecure($pompoms[$_GET['excel']][0]['nom']));
	$items = $pompoms[$_GET['excel']];

	exportXLSX($items, $fichier, $titre, $labels);

}

else if (isset($_GET['excel'])) {

	$fichier = 'liste_pompoms_ecoles';
	$items = $titres = $feuilles[] = [];
	
	$i = 0;
	foreach ($pompoms as $pompoms_ecole) {
		$titres[$i] = 'Liste des pompoms ('.unsecure($pompoms_ecole[0]['nom']).')';
		$feuilles[$i] = unsecure($pompoms_ecole[0]['nom']);
		$items[$i] = $pompoms_ecole;
		$i++;
	}
	
	exportXLSXGroupe($items, $fichier, $feuilles, $titres, $labels);

}


//Inclusion du bon fichier de template
require DIR.'templates/admin/competition/pompoms_ecoles.php';
