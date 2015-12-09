<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/competition/action_participants.php *******/
/* Liste des participants **********************************/
/* *********************************************************/
/* Dernière modification : le 23/02/15 *********************/
/* *********************************************************/

set_time_limit(60);

$participants = $pdo->query('SELECT '.
		'p.id, '.
		'e.nom AS enom, '.
		'e.ecole_lyonnaise, '.
		'p.nom AS pnom, '.
		'p.prenom AS pprenom, '.
		'p.sexe AS psexe, '.
		'p.telephone AS ptelephone, '.
		'p.id AS pid, '.
		'p.sportif, '.
		'p.fanfaron, '.
		'p.pompom, '.
		't.nom AS tnom, '.
		'r.montant AS recharge, '.
		'r.nom AS rnom, '.
		't.logement '.
	'FROM participants AS p '.
	'LEFT JOIN ecoles AS e ON '.
		'p.id_ecole = e.id '.
	'LEFT JOIN tarifs AS t ON '.
		't.id = p.id_tarif '.
	'LEFT JOIN recharges AS r ON '.
		'r.id = p.id_recharge '.
	'ORDER BY '.
		'p.nom ASC, '.
		'p.prenom ASC, '.
		'e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$participants = $participants->fetchAll(PDO::FETCH_ASSOC);


foreach ($participants as $i => $participant) {
	$participants[$i]['psportif'] = $participant['sportif'] ? 'Oui' : '';	
	$participants[$i]['pfanfaron'] = $participant['fanfaron'] ? 'Oui' : '';	
	$participants[$i]['ppompom'] = $participant['pompom'] ? 'Oui' : '';	
	$participants[$i]['plogement'] = $participant['logement'] ? 'Oui' : '';	
}

//Téléchargement du fichier XLSX concerné
if (isset($_GET['excel'])) {

	$titre = 'Liste des participants';
	$fichier = 'liste_participants';
	$items = $participants;
	$labels = [
		'Nom' => 'pnom',
		'Prénom' => 'pprenom',
		'Sexe' => 'psexe',
		'Ecole' => 'enom',
		'Sportif' => 'psportif',
		'Fanfaron' => 'pfanfaron',
		'Pompom' => 'ppompom',
		'Téléphone' => 'ptelephone',
		'Recharge' => 'recharge',
		'Tarif' => 'tnom',
		'Logement' => 'plogement'
	];
	exportXLSX($items, $fichier, $titre, $labels);

}


//Inclusion du bon fichier de template
require DIR.'templates/admin/competition/participants.php';
