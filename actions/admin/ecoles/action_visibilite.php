<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/admin/ecoles/action_visibilite.php **************/
/* Edition de la visibilité des tarifs *********************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/



$tarifs_ecoles = $pdo->query('SELECT '.
		'e.id, '.
		'e.nom, '.
		'ecole_lyonnaise, '.
		'COUNT(p.id) AS pnb, '.
		'te.id_tarif '.
	'FROM ecoles AS e '.
	'LEFT JOIN tarifs_ecoles AS te ON '.
		'te.id_ecole = e.id '.
	'LEFT JOIN participants AS p ON '.
		'p.id_tarif = te.id_tarif AND '.
		'p.id_ecole = e.id '.
	'GROUP BY '.
		'e.id, te.id_tarif '.
	'ORDER BY '.
		'ecole_lyonnaise DESC, '.
		'e.nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$tarifs_ecoles = $tarifs_ecoles->fetchAll(PDO::FETCH_ASSOC);


$tarifs = $pdo->query('SELECT '.
		'id, '.
		'nom, '.
		'type, '.
		'ecole_lyonnaise '.
	'FROM tarifs '.
	'ORDER BY '.
		'type ASC, '.
		'ordre ASC, '.
		'nom ASC')
	or DEBUG_ACTIVE && die(print_r($pdo->errorInfo()));
$tarifs = $tarifs->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);


$ecoles = [];
foreach ($tarifs_ecoles as $tarif) {
	if (!isset($ecoles[$tarif['id']]))
		$ecoles[$tarif['id']] = array_merge($tarif, array('tarifs' => []));

	$ecoles[$tarif['id']]['tarifs'][$tarif['id_tarif']] = $tarif['pnb'];
}


if (!empty($_POST['maj'])) {

	$ecole_lyonnaise = !empty($_POST['ecole_lyonnaise']);
	$sportif = !empty($_POST['sportif']);

	foreach ($ecoles as $eid => $ecole) {

		if ($ecole_lyonnaise &&
			!$ecole['ecole_lyonnaise'] ||
			!$ecole_lyonnaise &&
			$ecole['ecole_lyonnaise'])
			continue;
						
		foreach ($tarifs as $tid => $tarif) {

			if ($ecole_lyonnaise &&
				!$tarif['ecole_lyonnaise'] ||
				!$ecole_lyonnaise &&
				$tarif['ecole_lyonnaise'] ||
				!preg_match('`^'.($sportif ? '' : 'non').'sportif$`', $tarif['type']) ||
				!empty($ecoles[$eid]['tarifs'][$tid]))
				continue;
				

			$pdo->exec('DELETE FROM tarifs_ecoles WHERE '.
				'id_ecole = '.(int) $eid.' AND '.
				'id_tarif = '.(int) $tid);

			if (isset($_POST['tarifs_'.$eid]) &&
				is_array($_POST['tarifs_'.$eid]) &&
				in_array($tid, $_POST['tarifs_'.$eid])) {

				$ecoles[$eid]['tarifs'][$tid] = 0;

				$pdo->exec('INSERT INTO tarifs_ecoles SET '.
					'id_ecole = '.(int) $eid.', '.
					'id_tarif = '.(int) $tid);
			}

			else 
				unset($ecoles[$eid]['tarifs'][$tid]);

		}

	}

	$maj = true;

} 

//Inclusion du bon fichier de template
require DIR.'templates/admin/ecoles/visibilite.php';
