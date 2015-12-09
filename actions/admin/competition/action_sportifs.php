<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_sportifs.php ***********/
/* Liste des sportifs **************************************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/



$sportifs = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom AS enom, '.
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
	'FROM sportifs AS sp '.
	'LEFT JOIN ecoles AS e ON '.
		'sp.id_ecole = e.id '.
	'LEFT JOIN participants AS p ON '.
		'p.id = sp.id_participant '.
	'LEFT JOIN sports AS s ON '.
		's.id = sp.id_sport '.
	'LEFT JOIN equipes AS eq ON '.
		'eq.id_sport = s.id AND '.
		'eq.id_ecole = e.id '.
	'ORDER BY '.
		'p.nom ASC, '.
		'p.prenom ASC, '.
		'e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sportifs = $sportifs->fetchAll(PDO::FETCH_ASSOC);


foreach ($sportifs as $i => $sportif) {
	$sportifs[$i]['sport_sexe'] = $sportif['sport'].' '.strip_tags(printSexe($sportif['ssexe']));
	$sportifs[$i]['capitaine'] = $sportif['id_capitaine'] == $sportif['pid'] ? 'Oui' : '';	
}


//Téléchargement du fichier XLSX concerné
if (isset($_GET['excel'])) {

	$titre = 'Liste des sportifs';
	$fichier = 'liste_sportifs';
	$items = $sportifs;
	$labels = [
		'Capitaine' => 'capitaine',
		'Nom' => 'pnom',
		'Prénom' => 'pprenom',
		'Sexe' => 'psexe',
		'Ecole' => 'enom',
		'Sport' => 'sport_sexe',
		'Licence' => 'plicence',
		'Téléphone' => 'ptelephone',
	];
	exportXLSX($items, $fichier, $titre, $labels);

}


//Inclusion du bon fichier de template
require DIR.'templates/admin/competition/sportifs.php';
