<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/logement/action_filles.php ****************/
/* Liste des filles pouvant être logées ********************/
/* *********************************************************/
/* Dernière modification : le 16/02/15 *********************/
/* *********************************************************/



if (isset($_GET['maj']) &&
	isset($_POST['chambre']) &&
	!empty($_POST['pid'])) {

	$pdo->exec('DELETE FROM chambres_participants WHERE '.
		'id_participant = '.(int) $_POST['pid']);

	if (!empty($_POST['chambre']))
		$pdo->exec('INSERT INTO chambres_participants SET '.
			'id_participant = '.(int) $_POST['pid'].', '.
			'id_chambre = '.(int) $_POST['chambre']);

	die;

}

if (isset($_GET['maj']) &&
	isset($_POST['telephone']) &&
	!empty($_POST['pid'])) {

	$pdo->exec('UPDATE participants SET '.
			'telephone = "'.secure($_POST['telephone']).'" '.
		'WHERE '.
			'id = '.(int) $_POST['pid']);

}


if (!empty($_POST['add_amie']) && 
	!empty($_POST['chambre']) &&
	!empty($_POST['fille']) && 
	is_string($_POST['chambre']) &&
	preg_match('`[UVTXABC][0-9]{3}`', $_POST['chambre'])) {

	$batiment = $_POST['chambre'][0];
	$etage = $_POST['chambre'][1];
	$numero = substr($_POST['chambre'], 2);

	$chambre = $pdo->query('SELECT '.
			'id '.
		'FROM chambres WHERE '.
			'batiment = "'.$batiment.'" AND '.
			'etage = "'.$etage.'" AND '.
			'numero = "'.$numero.'"')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$chambre = $chambre->fetch(PDO::FETCH_ASSOC);

	if (!empty($chambre)) {
		$pdo->exec('INSERT INTO chambres_participants SET '.
			'id_participant = '.(int) $_POST['fille'].', '.
			'id_chambre = '.(int) $chambre['id']);
	}
}


if (isset($_GET['ajax'])) {

	$filtre = !empty($_POST['filtre']) ? trim($_POST['filtre']) : '';
	$sienne = !empty($_POST['chambre']) ? $_POST['chambre'] : '';

	$chambres_ = $pdo->query('SELECT '.
			'c.id, '.
			'c.batiment, '.
			'c.etage, '.
			'c.numero, '.
			'c.etat, '.
			'COUNT(cp.id_participant) AS filles '.
		'FROM chambres AS c '.
		'LEFT JOIN chambres_participants AS cp ON '.
			'cp.id_chambre = c.id '.
		'WHERE '.
			(empty($_POST['amies']) ? 'c.etat = "autorise" OR ' : '').
			'c.etat = "amies" '.
		'GROUP BY '.
			'c.id '.
		'ORDER BY '.
			'c.etat DESC, '.
			'c.batiment, '.
			'c.etage, '.
			'c.numero')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$chambres_ = $chambres_->fetchAll(PDO::FETCH_ASSOC);

	$chambres = [];
	if (empty($filtre) && 
		empty($_POST['amies']))
		$chambres[] = array(
			'id' => '',
			'value' => '(Sans chambre)',
			'numero' => '',
			'color' => 'transparent');

	foreach ($chambres_ as $chambre) {
		
		$numero = sprintf('%s%d%02d', $chambre['batiment'], $chambre['etage'], $chambre['numero']);
		$places = in_array($chambre['batiment'], str_split('ABC')) ? 5 : 3;
		$places -= $chambre['filles'];

		if (!empty($filtre) &&
			strpos($numero, $filtre) === false ||
			$places <= 0 && $sienne != $numero)
			continue;

		$chambres[] = array(
			'id' => $chambre['id'],
			'value' => $numero.(empty($_POST['amies']) && $chambre['etat'] == 'amies' ? ' #Amies ' : '').
				' ('.(empty($_POST['amies']) && $numero == $sienne ? 'La sienne' : 
				($places.' place'.($places > 1 ? 's' : ''))).')',
			'numero' => $numero,
			'color' => colorChambre($numero));
	}

	header('Content-Type: application/json', true);
	echo json_encode($chambres);
	exit;
}


$ecoles = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom '.
	'FROM ecoles AS e '.
	'ORDER BY '.
		'e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$ecoles = $ecoles->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);




if (!empty($_GET['ecole']) &&
	in_array($_GET['ecole'], array_keys($ecoles))) {

	if (!empty($_GET['del']) &&
		intval($_GET['del'])) 
		$pdo->exec('DELETE FROM chambres_participants WHERE id_participant = '.$_GET['del']);
	


	$filles = $pdo->query('SELECT '.
			'p.id, '.
			'p.nom, '.
			'e.nom AS enom, '.
			'p.prenom, '.
			's.id AS sid, '.
			's.sport, '.
			's.sexe, '.
			'p.telephone, '.
			'c.id AS cid, '.
			'c.batiment, '.
			'c.etage, '.
			'c.numero '.
		'FROM participants AS p '.
		'JOIN tarifs AS t ON '.
			't.id = p.id_tarif AND '.
			't.logement = 1 '.
		'JOIN ecoles AS e ON '.
			'e.id = p.id_ecole '.
		'LEFT JOIN sportifs AS sp ON '.
			'sp.id_participant = p.id '.
		'LEFT JOIN sports AS s ON '.
			's.id = sp.id_sport '.
		'LEFT JOIN chambres_participants AS cp ON '.
			'cp.id_participant = p.id '.
		'LEFT JOIN chambres AS c ON '.
			'c.id = cp.id_chambre '.
		'WHERE '.
			'p.sexe = "f" AND '.
			'p.id_ecole = '.(int) $_GET['ecole'].' '.
		'ORDER BY '.
			'e.nom ASC, '.
			's.sport ASC, '.
			'p.nom ASC, '.
			'p.prenom ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$filles = $filles->fetchAll(PDO::FETCH_ASSOC);


	$filles_non_logees = $pdo->query('SELECT '.
			'p.id, '.
			'p.nom, '.
			'p.prenom '.
		'FROM participants AS p '.
		'JOIN tarifs AS t ON '.
			't.id = p.id_tarif AND '.
			't.logement = 0 '.
		'JOIN ecoles AS e ON '.
			'e.id = p.id_ecole '.
		'WHERE '.
			'p.sexe = "f" AND '.
			'p.id_ecole = '.(int) $_GET['ecole'].' AND '.
			'p.id NOT IN (SELECT '.
					'cp.id_participant '.
				'FROM chambres_participants AS cp) '.
		'ORDER BY '.
			'p.nom ASC, '.
			'p.prenom ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$filles_non_logees = $filles_non_logees->fetchAll(PDO::FETCH_ASSOC);


	$amies_logees = $pdo->query('SELECT '.
			'p.id, '.
			'p.nom, '.
			'e.nom AS enom, '.
			'p.prenom, '.
			's.id AS sid, '.
			's.sport, '.
			's.sexe, '.
			'p.telephone, '.
			'c.id AS cid, '.
			'c.batiment, '.
			'c.etage, '.
			'c.numero '.
		'FROM participants AS p '.
		'JOIN tarifs AS t ON '.
			't.id = p.id_tarif AND '.
			't.logement = 0 '.
		'JOIN ecoles AS e ON '.
			'e.id = p.id_ecole '.
		'LEFT JOIN sportifs AS sp ON '.
			'sp.id_participant = p.id '.
		'LEFT JOIN sports AS s ON '.
			's.id = sp.id_sport '.
		'JOIN chambres_participants AS cp ON '.
			'cp.id_participant = p.id '.
		'JOIN chambres AS c ON '.
			'c.id = cp.id_chambre '.
		'WHERE '.
			'p.sexe = "f" AND '.
			'p.id_ecole = '.(int) $_GET['ecole'].' '.
		'ORDER BY '.
			'e.nom ASC, '.
			's.sport ASC, '.
			'p.nom ASC, '.
			'p.prenom ASC')
		or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
	$amies_logees = $amies_logees->fetchAll(PDO::FETCH_ASSOC);

}


//Inclusion du bon fichier de template
require DIR.'templates/admin/logement/filles.php';
