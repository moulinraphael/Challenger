<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_capitaines.php *********/
/* Liste des capitaines par école **************************/
/* *********************************************************/
/* Dernière modification : le 19/01/15 *********************/
/* *********************************************************/



$capitaines = $pdo->query('SELECT '.
		's.id AS sid, '.
		'e.nom AS enom, '.
		'e.ecole_lyonnaise, '.
		's.sport, '.
		's.sexe AS ssexe, '.
		'e.id AS eid, '.
		'p.nom AS pnom, '.
		'p.prenom AS pprenom, '.
		'p.licence AS plicence, '.
		'p.sexe AS psexe, '.
		'p.telephone AS ptelephone, '.
		'p.id AS id '.
	'FROM equipes AS eq '.
	'LEFT JOIN sports AS s ON '.
		'eq.id_sport = s.id '.
	'LEFT JOIN participants AS p ON '.
		'p.id = eq.id_capitaine '.
	'LEFT JOIN ecoles AS e ON '.
		'e.id = eq.id_ecole '.
	'ORDER BY '.
		'p.nom ASC, '.
		'p.prenom ASC ')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$capitaines = $capitaines->fetchAll(PDO::FETCH_ASSOC);


foreach ($capitaines as $i => $capitaine)
	$capitaines[$i]['sport_sexe'] = $capitaine['sport'].' '.strip_tags(printSexe($capitaine['ssexe']));


//Téléchargement du fichier XLSX concerné
if (isset($_GET['excel'])) {

	$titre = 'Liste des capitaines';
	$fichier = 'liste_capitaines';
	$items = $capitaines;
	$labels = [
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
require DIR.'templates/admin/competition/capitaines.php';
