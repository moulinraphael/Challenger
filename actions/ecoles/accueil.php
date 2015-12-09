<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/ecoles/accueil.php ******************************/
/* Accueil de l'administration *****************************/
/* *********************************************************/
/* Dernière modification : le 27/11/14 *********************/
/* *********************************************************/



if (empty($_SESSION['ecole']) ||
	empty($_SESSION['ecole']['user']))
	die(header('location:'.url('accueil', false, false)));


$ecole = $pdo->query('SELECT '.
		'e.*, '.
		'(SELECT COUNT(p1.id) FROM participants AS p1 WHERE p1.id_ecole = e.id) AS quota_inscriptions, '.
		'(SELECT COUNT(p2.id) FROM participants AS p2 WHERE p2.id_ecole = e.id AND p2.sportif = 1) AS quota_sportif_view, '.
		'(SELECT COUNT(p3.id) FROM participants AS p3 WHERE p3.id_ecole = e.id AND p3.pompom = 1) AS quota_pompom_view, '.
		'(SELECT COUNT(p4.id) FROM participants AS p4 WHERE p4.id_ecole = e.id AND p4.fanfaron = 1) AS quota_fanfaron_view, '.
		'(SELECT COUNT(p6.id) FROM participants AS p6 WHERE p6.id_ecole = e.id AND p6.pompom = 1 AND p6.sportif = 0) AS quota_pompom_nonsportif_view, '.
		'(SELECT COUNT(p7.id) FROM participants AS p7 WHERE p7.id_ecole = e.id AND p7.fanfaron = 1 AND p7.sportif = 0) AS quota_fanfaron_nonsportif_view, '.
		'(SELECT COUNT(p8.id) FROM participants AS p8 JOIN tarifs AS t8 ON t8.id = p8.id_tarif AND t8.logement = 1 WHERE p8.id_ecole = e.id AND p8.sexe = "f") AS quota_filles_logees_view, '.
		'(SELECT COUNT(p9.id) FROM participants AS p9 JOIN tarifs AS t9 ON t9.id = p9.id_tarif AND t9.logement = 1 WHERE p9.id_ecole = e.id AND p9.sexe = "h") AS quota_garcons_loges_view '.
	'FROM ecoles AS e '.
	'WHERE '.
		'e.id = '.(int) $_SESSION['ecole']['user'])
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$ecole = $ecole->fetch(PDO::FETCH_ASSOC);


if (empty($ecole) ||
	$ecole['etat_inscription'] == 'fermee') {
	unset($_SESSION['ecole']);
	die(header('location:'.url('', false, false)));
}



if ($ecole['etat_inscription'] == 'validee')
	die(header('location:'.url('ecole/recapitulatif', false, false)));


if ((!empty($_POST['save']) || !empty($_POST['continue'])) &&
	isset($_POST['adresse']) &&
	isset($_POST['code_postal']) && 
	isset($_POST['ville']) &&
	isset($_POST['email_ecole']) &&
	isset($_POST['telephone_ecole']) &&
	!empty($_POST['nom_respo']) &&
	!empty($_POST['prenom_respo']) &&
	!empty($_POST['email_respo']) &&
	!empty($_POST['telephone_respo']) &&
	!empty($_POST['nom_corespo']) &&
	!empty($_POST['prenom_corespo']) &&
	!empty($_POST['email_corespo']) &&
	!empty($_POST['telephone_corespo'])) {

	$pdo->exec('UPDATE ecoles SET '.
			'adresse = "'.secure($_POST['adresse']).'", '.
			'code_postal = "'.secure($_POST['code_postal']).'", '.
			'ville = "'.secure($_POST['ville']).'", '.
			'email_ecole = "'.secure($_POST['email_ecole']).'", '.
			'telephone_ecole = "'.secure($_POST['telephone_ecole']).'", '.
			'nom_respo = "'.secure($_POST['nom_respo']).'", '.
			'prenom_respo = "'.secure($_POST['prenom_respo']).'", '.
			'email_respo = "'.secure($_POST['email_respo']).'", '.
			'telephone_respo = "'.secure($_POST['telephone_respo']).'", '.
			'nom_corespo = "'.secure($_POST['nom_corespo']).'", '.
			'prenom_corespo = "'.secure($_POST['prenom_corespo']).'", '.
			'email_corespo = "'.secure($_POST['email_corespo']).'", '.
			'telephone_corespo = "'.secure($_POST['telephone_corespo']).'" '.
		'WHERE '.
			'id = '.$ecole['id']);
	

	$_POST['id'] = $ecole['id'];
	foreach ($_POST as $label => $value)
		if (!array_key_exists('login', $ecole))
			unset($_POST[$label]);

	foreach ($ecole as $label => $value)
		if (!isset($_POST[$label]))
			$_POST[$label] = $value;

	$ecole = $_POST;
	$erreur_maj = false;

	if (!empty($_POST['continue']))
		header('location:'.url('ecole/participants', false, false));


} else if (!empty($_POST['save']) || !empty($_POST['continue']))
	$erreur_maj = 'champs';


//Inclusion du bon fichier de template
require DIR.'templates/ecoles/accueil.php';