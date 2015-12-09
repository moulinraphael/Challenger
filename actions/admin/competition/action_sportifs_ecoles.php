<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_sportifs_ecoles.php ****/
/* Liste des sportifs par école ****************************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/



$sportifs = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom, '.
		'e.ecole_lyonnaise, '.
		's.sport, '.
		's.sexe AS ssexe, '.
		's.id AS sid, '.
		'e.quota_sportif, '.
		'p.nom AS pnom, '.
		'p.prenom AS pprenom, '.
		'p.licence AS plicence, '.
		'p.sexe AS psexe, '.
		'p.telephone AS ptelephone, '.
		'p.id AS pid, '.
		'eq.id_capitaine '.
	'FROM ecoles AS e '.
	'LEFT JOIN sportifs AS sp ON '.
		'sp.id_ecole = e.id '.
	'LEFT JOIN participants AS p ON '.
		'p.id = sp.id_participant '.
	'LEFT JOIN sports AS s ON '.
		's.id = sp.id_sport '.
	'LEFT JOIN equipes AS eq ON '.
		'eq.id_sport = s.id AND '.
		'eq.id_ecole = e.id '.
	'ORDER BY '.
		'e.nom ASC, '.
		'p.nom ASC, '.
		'p.prenom ASC ')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sportifs = $sportifs->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);


foreach ($sportifs as $i => $groupe) {
	foreach ($groupe as $j => $sportif) {
		$sportifs[$i][$j]['sport_sexe'] = $sportif['sport'].' '.strip_tags(printSexe($sportif['ssexe']));
		$sportifs[$i][$j]['capitaine'] = $sportif['id_capitaine'] == $sportif['pid'] ? 'Oui' : '';	
	}
}


//Labels pour le XLSX
$labels = [
	'Capitaine' => 'capitaine',
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
	in_array($_GET['excel'], array_keys($sportifs))) {

	$titre = 'Liste des sportifs ('.unsecure($sportifs[$_GET['excel']][0]['nom']).')';
	$fichier = 'liste_sportifs_ecole_'.onlyLetters(unsecure($sportifs[$_GET['excel']][0]['nom']));
	$items = $sportifs[$_GET['excel']];
	exportXLSX($items, $fichier, $titre, $labels);

}


else if (isset($_GET['excel'])) {

	$fichier = 'liste_sportifs_ecoles';
	$items = $titres = $feuilles[] = [];
	
	$i = 0;
	foreach ($sportifs as $sportifs_ecole) {
		$titres[$i] = 'Liste des sportifs ('.unsecure($sportifs_ecole[0]['nom']).')';
		$feuilles[$i] = unsecure($sportifs_ecole[0]['nom']);
		$items[$i] = $sportifs_ecole;
		$i++;
	}
	
	exportXLSXGroupe($items, $fichier, $feuilles, $titres, $labels);

}


//Inclusion du bon fichier de template
require DIR.'templates/admin/competition/sportifs_ecoles.php';
