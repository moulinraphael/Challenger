<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/ecoles/action_recharges.php ***************/
/* Définition des recharges ********************************/
/* *********************************************************/
/* Dernière modification : le 13/12/14 *********************/
/* *********************************************************/


//Ajout d'une recharge
if (isset($_POST['add']) &&
	!empty($_POST['nom'][0]) && 
	isset($_POST['montant'][0]) && 
	is_numeric($_POST['montant'][0])) {

	$pdo->exec('INSERT INTO recharges SET '.
		'nom = "'.secure($_POST['nom'][0]).'", '.
		'montant = '.abs((float) $_POST['montant'][0]));

	$add = true;
}


//On récupère l'indice du champ concerné
if ((!empty($_POST['delete']) || 
	!empty($_POST['edit'])) &&
	isset($_POST['id']) &&
	is_array($_POST['id']))
	$i = array_search(empty($_POST['delete']) ?
		$_POST['edit'] :
		$_POST['delete'],
		$_POST['id']);


//On edite une recharge
if (isset($i) &&
	empty($_POST['delete']) &&
	!empty($_POST['nom'][$i]) && 
	isset($_POST['montant'][$i]) && 
	is_numeric($_POST['montant'][$i])) {

	$pdo->exec('UPDATE recharges SET '.
			'nom = "'.secure($_POST['nom'][$i]).'", '.
			'montant = '.abs((float) $_POST['montant'][$i]).' '.
		'WHERE id = '.abs((int) $_POST['id'][$i]));
	
	$modify = true;
}


//On supprime une recharge
else if (isset($i) &&
	!empty($_POST['delete'])) {

	$pdo->exec('DELETE FROM recharges '.
		'WHERE id = '.abs((int) $_POST['id'][$i]));

	$delete = true;
}


$recharges = $pdo->query('SELECT '.
		'r.*, '.
		'COUNT(p.id_ecole) AS pid '.
	'FROM recharges AS r '.
	'LEFT JOIN participants AS p ON '.
		'p.id_recharge = r.id '.
	'GROUP BY '.
		'r.id '.
	'ORDER BY '.
		'montant ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$recharges = $recharges->fetchAll(PDO::FETCH_ASSOC);


//Inclusion du bon fichier de template
require DIR.'templates/admin/ecoles/recharges.php';
