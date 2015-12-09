<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_fanfarons.php **********/
/* Liste des fanfarons *************************************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/



$fanfarons = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom AS enom, '.
		'e.ecole_lyonnaise, '.
		'e.quota_fanfaron, '.
		'p.nom AS pnom, '.
		'p.prenom AS pprenom, '.
		'p.sexe AS psexe, '.
		'p.telephone AS ptelephone, '.
		'p.id AS pid '.
	'FROM participants AS p '.
	'LEFT JOIN ecoles AS e ON '.
		'p.id_ecole = e.id '.
	'WHERE '.
		'p.fanfaron = 1 '.
	'ORDER BY '.
		'p.nom ASC, '.
		'p.prenom ASC, '.
		'e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$fanfarons = $fanfarons->fetchAll(PDO::FETCH_ASSOC);


//Téléchargement du fichier XLSX concerné
if (isset($_GET['excel'])) {

	$titre = 'Liste des fanfarons';
	$fichier = 'liste_fanfarons';
	$items = $fanfarons;
	$labels = [
		'Nom' => 'pnom',
		'Prénom' => 'pprenom',
		'Sexe' => 'psexe',
		'Ecole' => 'enom',
		'Téléphone' => 'ptelephone',
	];
	exportXLSX($items, $fichier, $titre, $labels);

}


//Inclusion du bon fichier de template
require DIR.'templates/admin/competition/fanfarons.php';
