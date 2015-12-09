<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/logement/action_chambres_batiment.php *****/
/* Liste des chambres dans le batiment concerné ************/
/* *********************************************************/
/* Dernière modification : le 19/01/15 *********************/
/* *********************************************************/


if (isset($_GET['maj']) &&
	!empty($_POST['chambre']) &&
	is_string($_POST['chambre']) &&
	strlen($_POST['chambre']) == 4 &&
	in_array($_POST['clef'], array_keys($labelsEtatClef)) &&
	isset($_POST['lit'])) {
	
	$batiment = $_POST['chambre'][0];
	$etage = $_POST['chambre'][1];
	$numero = $_POST['chambre'][2].$_POST['chambre'][3];


	$existe = $pdo->query('SELECT '.
			'c.id, '.
			'c.etat, '.
			'COUNT(cp.id_participant) AS cp '.
		'FROM chambres AS c '.
		'LEFT JOIN chambres_participants AS cp ON '.
			'cp.id_chambre = c.id '.
		'WHERE '.
			'batiment = "'.secure($batiment).'" AND '.
			'etage = "'.secure($etage).'" AND '.
			'numero = "'.secure($numero).'" '.
		'GROUP BY '.
			'c.id')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$existe = $existe->fetch(PDO::FETCH_ASSOC);

	if (empty($existe) ||
		!in_array($existe['etat'], array('amies', 'autorise')))
		die;


	$pdo->exec('UPDATE chambres SET '.
			'etat_clef = "'.secure($_POST['clef']).'", '.
			'lit_camp = '.(!empty($_POST['lit']) ? '1' : '0').' '.
		'WHERE '.
			'batiment = "'.secure($batiment).'" AND '.
			'etage = "'.secure($etage).'" AND '.
			'numero = "'.secure($numero).'"'); 

	die;
}

$batiment = str_replace('_', '', $args[2][0]);
$proprios_ = $pdo->query('SELECT '.
		'c.id, '.
		'c.etage, '.
		'c.numero, '.
		'c.nom_proprio, '.
		'c.prenom_proprio, '.
		'c.surnom_proprio, '.
		'c.email_proprio, '.
		'c.telephone_proprio, '.
		'c.etat, '.
		'c.etat_clef, '.
		'c.lit_camp, '.
		'GROUP_CONCAT(cp.id_participant, ",") AS filles '.
	'FROM chambres AS c '.
	'LEFT JOIN chambres_participants AS cp ON '.
		'cp.id_chambre = c.id '.
	'WHERE '.
		'c.batiment = "'.$batiment.'" AND ('.
			'c.etat = "autorise" OR '.
			'c.etat = "amies") '.
	'GROUP BY '.
		'c.id')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$proprios_ = $proprios_->fetchAll(PDO::FETCH_ASSOC);


$filles = $pdo->query('SELECT '.
		'p.id, '.
		'p.nom, '.
		'e.nom AS enom, '.
		'p.prenom, '.
		's.id AS sid, '.
		's.sport, '.
		's.sexe, '.
		'p.telephone '.
	'FROM participants AS p '.
	'JOIN tarifs AS t ON '.
		't.id = p.id_tarif '.
	'JOIN ecoles AS e ON '.
		'e.id = p.id_ecole '.
	'LEFT JOIN sportifs AS sp ON '.
		'sp.id_participant = p.id '.
	'LEFT JOIN sports AS s ON '.
		's.id = sp.id_sport '.
	'WHERE '.
		'p.sexe = "f" '.
	'ORDER BY '.
		'e.nom ASC, '.
		's.sport ASC, '.
		'p.nom ASC, '.
		'p.prenom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$filles = $filles->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);



$proprios = array();
foreach ($proprios_ as $proprio)
	$proprios[sprintf('%s%d%02d', $batiment, $proprio['etage'], (int) $proprio['numero'])] = $proprio;


//Inclusion du bon fichier de template
require DIR.'templates/admin/logement/chambres_batiment.php';
