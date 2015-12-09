<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_sans_sport.php *********/
/* Liste des sportifs sans sport ***************************/
/* *********************************************************/
/* Dernière modification : le 20/01/15 *********************/
/* *********************************************************/


$sans_sport = $pdo->query('SELECT '.
		'p.id, '.
		'p.nom, '.
		'p.prenom, '.
		'p.sexe, '.
		'p.telephone, '.
		'p.licence, '.
		'e.nom AS enom '.
	'FROM participants AS p '.
	'JOIN ecoles AS e ON '.
		'e.id = p.id_ecole '.
	'WHERE '.
		'p.sportif = 1 AND '.
		'p.id NOT IN (SELECT '.
				's.id_participant '.
			'FROM sportifs AS s WHERE '.
				's.id_ecole = e.id) '.
	'ORDER BY '.
		'p.nom ASC, '.
		'p.prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sans_sport = $sans_sport->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


//Téléchargement du fichier XLSX concerné
if (isset($_GET['excel'])) {

	$titre = 'Liste des sportifs sans sport';
	$fichier = 'liste_sans_sport';
	$items = $sans_sport;
	$labels = [
		'Nom' => 'nom',
		'Prénom' => 'prenom',
		'Sexe' => 'sexe',
		'Ecole' => 'enom',
		'Licence' => 'licence',
		'Téléphone' => 'telephone',
	];
	exportXLSX($items, $fichier, $titre, $labels);

}


//Inclusion du bon fichier de template
require DIR.'templates/admin/competition/sans_sport.php';
