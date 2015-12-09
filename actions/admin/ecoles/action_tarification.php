<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/ecoles/action_tarification.php ************/
/* Définition des tarifs accessibles par les école *********/
/* *********************************************************/
/* Dernière modification : le 03/12/14 *********************/
/* *********************************************************/

$sports = $pdo->query('SELECT '.
		'id, '.
		'sport, '.
		'sexe '.
	'FROM sports '.
	'ORDER BY '.
		'sport ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$sports = $sports->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


//Ajout d'un tarif
if (isset($_POST['add']) &&
	isset($_POST['ecole_lyonnaise'][0]) &&
	in_array($_POST['ecole_lyonnaise'][0], ['0', '1']) &&
	isset($_POST['for_pompom'][0]) &&
	in_array($_POST['for_pompom'][0], ['yes', 'or', 'no']) &&
	isset($_POST['for_cameraman'][0]) &&
	in_array($_POST['for_cameraman'][0], ['yes', 'or', 'no']) &&
	isset($_POST['for_fanfaron'][0]) &&
	in_array($_POST['for_fanfaron'][0], ['yes', 'or', 'no']) &&
	!empty($_POST['type'][0]) &&
	in_array($_POST['type'][0], array_keys($typesTarifs)) &&
	!empty($_POST['ordre'][0]) && 
	intval($_POST['ordre'][0]) &&
	!empty($_POST['nom'][0]) && 
	!empty($_POST['description'][0]) && 
	isset($_POST['montant'][0]) && 
	isset($_POST['special'][0]) && (
		empty($_POST['special'][0]) ||
		in_array($_POST['special'][0], array_keys($sports))) &&
	is_numeric($_POST['montant'][0])) {

	if (!isset($_POST['logement'])) $_POST['logement'] = array();

	$pdo->exec($s = 'INSERT INTO tarifs SET '.
		'nom = "'.secure($_POST['nom'][0]).'", '.
		'description = "'.secure($_POST['description'][0]).'", '.
		'tarif = '.abs((float) $_POST['montant'][0]).', '.
		'ecole_lyonnaise = '.$_POST['ecole_lyonnaise'][0].', '.
		'for_pompom = "'.$_POST['for_pompom'][0].'", '.
		'for_cameraman = "'.$_POST['for_cameraman'][0].'", '.
		'for_fanfaron = "'.$_POST['for_fanfaron'][0].'", '.
		'type = "'.secure($_POST['type'][0]).'", '.
		'logement = '.(in_array('0', $_POST['logement']) ? '1' : '0').', '.
		'id_sport_special = '.(empty($_POST['special'][0]) || $_POST['type'][0] == 'nonsportif' ? 'NULL' : $_POST['special'][0]).', '.
		'ordre = '.abs((int) $_POST['ordre'][0]));

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


//On edite un tarif
if (isset($i) &&
	empty($_POST['delete']) &&
	isset($_POST['ecole_lyonnaise'][$i]) &&
	in_array($_POST['ecole_lyonnaise'][$i], ['0', '1']) &&
	isset($_POST['for_pompom'][$i]) &&
	in_array($_POST['for_pompom'][$i], ['yes', 'or', 'no']) &&
	isset($_POST['for_cameraman'][$i]) &&
	in_array($_POST['for_cameraman'][$i], ['yes', 'or', 'no']) &&
	isset($_POST['for_fanfaron'][$i]) &&
	in_array($_POST['for_fanfaron'][$i], ['yes', 'or', 'no']) &&
	!empty($_POST['type'][$i]) &&
	in_array($_POST['type'][$i], array_keys($typesTarifs)) &&
	!empty($_POST['ordre'][$i]) && 
	intval($_POST['ordre'][$i]) &&
	!empty($_POST['nom'][$i]) && 
	!empty($_POST['description'][$i]) && 
	isset($_POST['montant'][$i]) && 
	isset($_POST['special'][$i]) && (
		empty($_POST['special'][$i]) ||
		in_array($_POST['special'][$i], array_keys($sports))) &&
	is_numeric($_POST['montant'][$i])) {

	if (!isset($_POST['logement'])) $_POST['logement'] = array();

	$pdo->exec('UPDATE tarifs SET '.
			'nom = "'.secure($_POST['nom'][$i]).'", '.
			'description = "'.secure($_POST['description'][$i]).'", '.
			'tarif = '.abs((float) $_POST['montant'][$i]).', '.
			'ecole_lyonnaise = '.$_POST['ecole_lyonnaise'][$i].', '.
			'for_pompom = "'.$_POST['for_pompom'][$i].'", '.
			'for_cameraman = "'.$_POST['for_cameraman'][$i].'", '.
			'for_fanfaron = "'.$_POST['for_fanfaron'][$i].'", '.
			'type = "'.secure($_POST['type'][$i]).'", '.
			'logement = '.(in_array($_POST['id'][$i], $_POST['logement']) ? '1' : '0').', '.
			'id_sport_special = '.(empty($_POST['special'][$i]) || $_POST['type'][$i] == 'nonsportif' ? 'NULL' : $_POST['special'][$i]).', '.
			'ordre = '.abs((int) $_POST['ordre'][$i]).' '.
		'WHERE id = '.abs((int) $_POST['id'][$i]));
	
	$modify = true;
}


//On supprime un tarif
else if (isset($i) &&
	!empty($_POST['delete'])) {

	$pdo->exec('DELETE FROM tarifs '.
		'WHERE id = '.abs((int) $_POST['id'][$i]));

	$delete = true;
}


$tarifs_lyonnais = $pdo->query('SELECT '.
		't.*, '.
		'COUNT(te.id_ecole) AS teid '.
	'FROM tarifs AS t '.
	'LEFT JOIN tarifs_ecoles AS te ON '.
		'te.id_tarif = t.id '.
	'WHERE '.
		't.ecole_lyonnaise = 1 '.
	'GROUP BY '.
		't.id '.
	'ORDER BY '.
		'ordre ASC, '.
		'nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$tarifs_lyonnais = $tarifs_lyonnais->fetchAll(PDO::FETCH_ASSOC);


$tarifs_nonlyonnais = $pdo->query('SELECT '.
		't.*, '.
		'COUNT(te.id_ecole) AS teid '.
	'FROM tarifs AS t '.
	'LEFT JOIN tarifs_ecoles AS te ON '.
		'te.id_tarif = t.id '.
	'WHERE '.
		't.ecole_lyonnaise = 0 '.
	'GROUP BY '.
		't.id '.
	'ORDER BY '.
		'ordre ASC, '.
		'nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$tarifs_nonlyonnais = $tarifs_nonlyonnais->fetchAll(PDO::FETCH_ASSOC);


//Inclusion du bon fichier de template
require DIR.'templates/admin/ecoles/tarification.php';
