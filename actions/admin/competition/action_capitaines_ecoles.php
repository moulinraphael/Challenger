<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_capitaines_ecoles.php **/
/* Liste des capitaines par école **************************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/



$capitaines = $pdo->query('SELECT '.
		'e.id AS id, '.
		'e.nom, '.
		'e.ecole_lyonnaise, '.
		's.sport, '.
		's.sexe AS ssexe, '.
		's.id AS sid, '.
		'p.nom AS pnom, '.
		'p.prenom AS pprenom, '.
		'p.licence AS plicence, '.
		'p.sexe AS psexe, '.
		'p.telephone AS ptelephone, '.
		'p.id AS pid '.
	'FROM ecoles AS e '.
	'LEFT JOIN equipes AS eq ON '.
		'eq.id_ecole = e.id '.
	'LEFT JOIN participants AS p ON '.
		'p.id = eq.id_capitaine '.
	'LEFT JOIN sports AS s ON '.
		's.id = eq.id_sport '.
	'ORDER BY '.
		'e.nom ASC, '.
		//'s.sport ASC, '.
		'p.nom ASC, '.
		'p.prenom ASC ')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$capitaines = $capitaines->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);


foreach ($capitaines as $i => $groupe) {
	foreach ($groupe as $j => $capitaine) {	
		$capitaines[$i][$j]['sport_sexe'] = $capitaine['sport'].' '.strip_tags(printSexe($capitaine['ssexe']));
	}
}


//Labels XLSX
$labels = [
	'Nom' => 'pnom',
	'Prénom' => 'pprenom',
	'Sexe' => 'psexe',
	'Sport' => 'sport_sexe',
	'Licence' => 'plicence',
	'Téléphone' => 'ptelephone',
];


//Téléchargement du fichier XLSX concerné
if (!empty($_GET['excel']) &&
	intval($_GET['excel']) &&
	in_array($_GET['excel'], array_keys($capitaines))) {

	$titre = 'Liste des capitaines ('.unsecure($capitaines[$_GET['excel']][0]['nom']).')';
	$fichier = 'liste_capitaines_ecole_'.onlyLetters(unsecure($capitaines[$_GET['excel']][0]['nom']));
	$items = $capitaines[$_GET['excel']];
	
	exportXLSX($items, $fichier, $titre, $labels);

}

else if (isset($_GET['excel'])) {

	$fichier = 'liste_capitaines_ecoles';
	$items = $titres = $feuilles[] = [];
	
	$i = 0;
	foreach ($capitaines as $capitaines_ecole) {
		$titres[$i] = 'Liste des capitaines ('.unsecure($capitaines_ecole[0]['nom']).')';
		$feuilles[$i] = unsecure($capitaines_ecole[0]['nom']);
		$items[$i] = $capitaines_ecole;
		$i++;
	}
	
	exportXLSXGroupe($items, $fichier, $feuilles, $titres, $labels);

}




//Inclusion du bon fichier de template
require DIR.'templates/admin/competition/capitaines_ecoles.php';
