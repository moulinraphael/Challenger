<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/logement/action_recensement_batiment.php **/
/* Liste des chambres dans le batiment concerné ************/
/* *********************************************************/
/* Dernière modification : le 19/01/15 *********************/
/* *********************************************************/


if (isset($_GET['maj']) &&
	!empty($_POST['chambre']) &&
	is_string($_POST['chambre']) &&
	strlen($_POST['chambre']) == 4 &&
	isset($_POST['nom']) &&
	isset($_POST['prenom']) &&
	isset($_POST['surnom']) &&
	isset($_POST['email']) &&
	isset($_POST['telephone']) &&
	isset($_POST['etat']) &&
	in_array($_POST['etat'], array_keys($labelsEtatChambre))) {

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

	if (!empty($existe) && 
		$existe['cp'] &&
		$existe['etat'] != $_POST['etat'])
		die(json_encode(array(
			'error' => 1,
			'etat' => empty($existe['etat']) ? 'pas_contacte' : $existe['etat'])));


	if (!empty($existe))
		$pdo->exec('UPDATE chambres SET '.
				'nom_proprio = "'.secure($_POST['nom']).'", '.
				'prenom_proprio = "'.secure($_POST['prenom']).'", '.
				'surnom_proprio = "'.secure($_POST['surnom']).'", '.
				'telephone_proprio = "'.secure($_POST['telephone']).'", '.
				'email_proprio = "'.secure($_POST['email']).'", '.
				'etat = "'.secure($_POST['etat']).'" '.
			'WHERE '.
				'batiment = "'.secure($batiment).'" AND '.
				'etage = "'.secure($etage).'" AND '.
				'numero = "'.secure($numero).'"'); 

	else
		$pdo->exec('INSERT INTO chambres SET '.
			'batiment = "'.secure($batiment).'", '.
			'etage = "'.secure($etage).'", '.
			'numero = "'.secure($numero).'", '.
			'nom_proprio = "'.secure($_POST['nom']).'", '.
			'prenom_proprio = "'.secure($_POST['prenom']).'", '.
			'surnom_proprio = "'.secure($_POST['surnom']).'", '.
			'telephone_proprio = "'.secure($_POST['telephone']).'", '.
			'email_proprio = "'.secure($_POST['email']).'", '.
			'etat = "'.secure($_POST['etat']).'"');

	die(json_encode(array('error' => 0)));
}

$batiment = $args[2][0];
$proprios_ = $pdo->query('SELECT '.
		'c.id, '.
		'c.etage, '.
		'c.numero, '.
		'c.nom_proprio, '.
		'c.prenom_proprio, '.
		'c.surnom_proprio, '.
		'c.email_proprio, '.
		'c.telephone_proprio, '.
		'c.etat '.
	'FROM chambres AS c '.
	'WHERE '.
		'c.batiment = "'.$batiment.'"')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$proprios_ = $proprios_->fetchAll(PDO::FETCH_ASSOC);


$proprios = array();
foreach ($proprios_ as $proprio)
	$proprios[sprintf('%s%d%02d', $batiment, $proprio['etage'], (int) $proprio['numero'])] = $proprio;


//Inclusion du bon fichier de template
require DIR.'templates/admin/logement/recensement_batiment.php';
