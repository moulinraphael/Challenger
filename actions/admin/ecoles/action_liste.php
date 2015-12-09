<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/ecoles/action_liste.php *******************/
/* Liste des Ecoles ****************************************/
/* *********************************************************/
/* Dernière modification : le 21/11/14 *********************/
/* *********************************************************/

if (!empty($_POST['del_ecole']) &&
	!empty($_POST['id']) &&
	intval($_POST['id'])) {
	$pdo->exec('DELETE FROM ecoles_sports WHERE id_ecole = '.(int) $_POST['id']);
	$pdo->exec('DELETE FROM equipes WHERE id_ecole = '.(int) $_POST['id']);
	$pdo->exec('DELETE FROM sportifs WHERE id_ecole = '.(int) $_POST['id']);
	$pdo->exec('DELETE FROM paiements WHERE id_ecole = '.(int) $_POST['id']);
	$pdo->exec('DELETE FROM participants WHERE id_ecole = '.(int) $_POST['id']);
	$pdo->exec('DELETE FROM ecoles WHERE id = '.(int) $_POST['id']);

	$delete = true;
}

if (!empty($_POST['empty_ecole']) &&
	!empty($_POST['id']) &&
	intval($_POST['id'])) {
	$pdo->exec('DELETE FROM ecoles_sports WHERE id_ecole = '.(int) $_POST['id']);
	$pdo->exec('DELETE FROM equipes WHERE id_ecole = '.(int) $_POST['id']);
	$pdo->exec('DELETE FROM sportifs WHERE id_ecole = '.(int) $_POST['id']);
	$pdo->exec('DELETE FROM paiements WHERE id_ecole = '.(int) $_POST['id']);
	$pdo->exec('DELETE FROM participants WHERE id_ecole = '.(int) $_POST['id']);

	$empty = true;
}


if (!empty($_POST['add_ecole']) &&
	!empty($_POST['nom'])) {

	$pdo->exec('INSERT INTO ecoles SET '.
		'nom = "'.secure($_POST['nom']).'"');

	$id = $pdo->lastInsertId();

	die(header('location:'.url('admin/module/ecoles/'.$id, false, false)));
}


$ecoles = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom, '.
		'e.login, '.
		'e.quota_total, '.
		'e.etat_inscription, '.
		'(SELECT COUNT(p1.id) FROM participants AS p1 WHERE p1.id_ecole = e.id) AS quota_inscriptions, '.
		'(SELECT COUNT(p2.id) FROM participants AS p2 WHERE p2.id_ecole = e.id AND p2.sportif = 1) AS quota_sportif, '.
		'(SELECT COUNT(p3.id) FROM participants AS p3 WHERE p3.id_ecole = e.id AND p3.pompom = 1) AS quota_pompom, '.
		'(SELECT COUNT(p4.id) FROM participants AS p4 WHERE p4.id_ecole = e.id AND p4.fanfaron = 1) AS quota_fanfaron, '.
		'(SELECT COUNT(p5.id) FROM participants AS p5 WHERE p5.id_ecole = e.id AND p5.cameraman = 1) AS quota_cameraman '.
	'FROM ecoles AS e '.
	'ORDER BY e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$ecoles = $ecoles->fetchAll(PDO::FETCH_ASSOC);


//Inclusion du bon fichier de template
require DIR.'templates/admin/ecoles/liste.php';
