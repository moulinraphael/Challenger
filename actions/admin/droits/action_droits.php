<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/droits/action_droits.php ******************/
/* Edition des droits **************************************/
/* *********************************************************/
/* Dernière modification : le 24/11/14 *********************/
/* *********************************************************/



$droits_admins = $pdo->query('SELECT '.
		'id, '.
		'nom, '.
		'prenom, '.
		'login, '.
		'module '.
	'FROM admins AS a '.
	'LEFT JOIN droits_admins AS da ON '.
		'da.id_admin = a.id '.
	'ORDER BY '.
		'nom ASC, '.
		'prenom ASC, '.
		'login ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$droits_admins = $droits_admins->fetchAll(PDO::FETCH_ASSOC);


$admins = [];
foreach ($droits_admins as $droit) {
	if (!isset($admins[$droit['id']]))
		$admins[$droit['id']] = array_merge($droit, array('modules' => []));

	$admins[$droit['id']]['modules'][] = $droit['module'];
}

foreach ($_POST as $name => $value) {
	if (preg_match('`^droit_([0-9]+)_(.+)$`', $name, $matches)) {
		
		if ($matches[1] == $_SESSION['admin']['user'])
			break;

		if (!empty($admins[$matches[1]]) &&
			in_array($matches[2], array_keys($modulesAdmin)) &&
			in_array($matches[2], $admins[$matches[1]]['modules'])) {
			
			$pdo->exec('DELETE FROM droits_admins WHERE '.
				'id_admin = '.(int) $matches[1].' AND '.
				'module = "'.secure($matches[2]).'"');

			foreach ($admins[$matches[1]]['modules'] as $k => $v)
				if ($v == $matches[2])
					unset($admins[$matches[1]]['modules'][$k]);
		}

		else if (!empty($admins[$matches[1]]) &&
			in_array($matches[2], array_keys($modulesAdmin)) &&
			!in_array($matches[2], $admins[$matches[1]]['modules'])) {
			
			$pdo->exec('INSERT INTO droits_admins SET '.
				'id_admin = '.(int) $matches[1].', '.
				'module = "'.secure($matches[2]).'"');

			$admins[$matches[1]]['modules'][] = $matches[2];
		}
	}
} 

//Inclusion du bon fichier de template
require DIR.'templates/admin/droits/droits.php';
