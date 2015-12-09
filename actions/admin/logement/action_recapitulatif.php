<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/logement/action_recapitulaltif.php ********/
/* Récapitualitf sur les logements *************************/
/* *********************************************************/
/* Dernière modification : le 16/02/15 *********************/
/* *********************************************************/


$batiments = $pdo->query('SELECT '.
		'c0.batiment, '.
		'(SELECT '.
				'COUNT(c1.id) '.
			'FROM chambres AS c1 WHERE '.
				'c1.etat = "amies" AND '.
				'c1.batiment = c0.batiment) AS nb_heberge_amies, '.
		'(SELECT '.
				'COUNT(c2.id) '.
			'FROM chambres AS c2 WHERE '.
				'c2.etat = "autorise" AND '.
				'c2.batiment = c0.batiment) AS nb_autorise, '.
		'(SELECT '.
				'COUNT(cp.id_participant) '.
			'FROM chambres_participants AS cp '.
			'JOIN chambres AS c4 ON '.
				'c4.id = cp.id_chambre '.
			'WHERE '.
				'c4.batiment = c0.batiment) AS nb_filles '.
	'FROM chambres AS c0 '.
	'GROUP BY c0.batiment')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$batiments = $batiments->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$total = array(
	'nb_heberge_amies' => 0,
	'nb_autorise' => 0,
	'nb_filles' => 0,
	'nb_chambres' => 0);


foreach (str_split('UVTXABC') AS $batiment) {

	if (empty($batiments[$batiment]))
		$batiments[$batiment] = array(
			'nb_heberge_amies' => 0,
			'nb_autorise' => 0,
			'nb_filles' => 0);

	$batiments[$batiment]['nb_etages'] = in_array($batiment, array('A', 'B', 'C')) ? 4 : 6;
	$batiments[$batiment]['nb_filles_max_chambre'] = in_array($batiment, array('A', 'B', 'C')) ? 5 : 3;
	$batiments[$batiment]['nb_chambres'] = 0;
	

	foreach (range(0, $batiments[$batiment]['nb_etages']) as $etage) {
		$batiments[$batiment]['nb_chambres'] += $etage == 0 ? 
			($batiments[$batiment]['nb_etages'] == 6 ? 8 : 17) :
			($batiments[$batiment]['nb_etages'] == 6 ? 16 : 17);
	}


	$total['nb_heberge_amies'] += $batiments[$batiment]['nb_heberge_amies'];
	$total['nb_autorise'] += $batiments[$batiment]['nb_autorise'];
	$total['nb_filles'] += $batiments[$batiment]['nb_filles'];
	$total['nb_chambres'] += $batiments[$batiment]['nb_chambres'];

}


$nb_filles = $pdo->query('SELECT '.
		'COUNT(p.id) AS tot '.
	'FROM participants AS p '.
	'JOIN tarifs AS t ON '.
		't.id = p.id_tarif AND '.
		't.logement = 1 '.
	'WHERE '.
		'p.sexe = "f"')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$nb_filles = $nb_filles->fetch(PDO::FETCH_ASSOC);


//Inclusion du bon fichier de template
require DIR.'templates/admin/logement/recapitulatif.php';
