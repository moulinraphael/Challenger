<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_pompoms.php ************/
/* Liste des pompoms ***************************************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/



$pompoms = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom AS enom, '.
		'e.ecole_lyonnaise, '.
		'e.quota_pompom, '.
		'p.nom AS pnom, '.
		'p.prenom AS pprenom, '.
		'p.sexe AS psexe, '.
		'p.telephone AS ptelephone, '.
		'p.id AS pid '.
	'FROM participants AS p '.
	'LEFT JOIN ecoles AS e ON '.
		'p.id_ecole = e.id '.
	'WHERE '.
		'p.pompom = 1 '.
	'ORDER BY '.
		'p.nom ASC, '.
		'p.prenom ASC, '.
		'e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$pompoms = $pompoms->fetchAll(PDO::FETCH_ASSOC);


//Téléchargement du fichier XLSX concerné
if (isset($_GET['excel'])) {

	$titre = 'Liste des pompoms';
	$fichier = 'liste_pompoms';
	$items = $pompoms;
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
require DIR.'templates/admin/competition/pompoms.php';
