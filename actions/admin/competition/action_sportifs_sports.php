<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_sportifs_sports.php ****/
/* Liste des sportifs **************************************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/



$sportifs = $pdo->query('SELECT '.
		's.id, '.
		's.sport, '.
		's.sexe, '.
		's.quota_max, '.
		's.quota_inscription, '.
		'p.nom AS pnom, '.
		'p.prenom AS pprenom, '.
		'p.licence AS plicence, '.
		'p.sexe AS psexe, '.
		'p.telephone AS ptelephone, '.
		'p.id AS pid, '.
		'eq.id_capitaine, '.
		'e.nom AS enom '.
	'FROM sports AS s '.
	'LEFT JOIN sportifs AS sp ON '.
		'sp.id_sport = s.id '.
	'LEFT JOIN participants AS p ON '.
		'p.id = sp.id_participant '.
	'LEFT JOIN ecoles AS e ON '.
		'e.id = sp.id_ecole '.
	'LEFT JOIN equipes AS eq ON '.
		'eq.id_sport = s.id AND '.
		'eq.id_ecole = e.id '.
	'ORDER BY '.
		's.sport ASC, '.
		's.sexe ASC, '.
		'p.nom ASC, '.
		'p.prenom ASC ')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sportifs = $sportifs->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);


foreach ($sportifs as $i => $groupe) {
	foreach ($groupe as $j => $sportif) {
		$sportifs[$i][$j]['capitaine'] = $sportif['id_capitaine'] == $sportif['pid'] ? 'Oui' : '';	
	}
}


//Labels pour le XLSX
$labels = [
	'Capitaine' => 'capitaine',
	'Nom' => 'pnom',
	'Prénom' => 'pprenom',
	'Sexe' => 'psexe',
	'Ecole' => 'enom',
	'Licence' => 'plicence',
	'Téléphone' => 'ptelephone',
];


//Téléchargement du fichier XLSX concerné
if (!empty($_GET['excel']) &&
	intval($_GET['excel']) &&
	in_array($_GET['excel'], array_keys($sportifs))) {

	$titre = 'Liste des sportifs ('.unsecure($sportifs[$_GET['excel']][0]['sport'].' '.strip_tags(printSexe($sportifs[$_GET['excel']][0]['sexe']))).')';
	$fichier = 'liste_sportifs_sport_'.onlyLetters(unsecure($sportifs[$_GET['excel']][0]['sport'].'_'.strip_tags(printSexe($sportifs[$_GET['excel']][0]['sexe']))));
	$items = $sportifs[$_GET['excel']];
	
	exportXLSX($items, $fichier, $titre, $labels);

}


else if (isset($_GET['excel'])) {

	$fichier = 'liste_sportifs_sports';
	$items = $titres = $feuilles[] = [];
	
	$i = 0;
	foreach ($sportifs as $sportifs_sport) {
		$titres[$i] = 'Liste des sportifs ('.unsecure($sportifs_sport[0]['sport'].' '.strip_tags(printSexe($sportifs_sport[0]['sexe']))).')';
		$feuilles[$i] = unsecure($sportifs_sport[0]['sport'].' '.strip_tags(printSexe($sportifs_sport[0]['sexe'])));
		$items[$i] = $sportifs_sport;
		$i++;
	}
	
	exportXLSXGroupe($items, $fichier, $feuilles, $titres, $labels);

}


//Inclusion du bon fichier de template
require DIR.'templates/admin/competition/sportifs_sports.php';
