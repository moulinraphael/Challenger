<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* includes/_challenger_functions.php **********************/
/* Fonctions relatives à l'application du Challenger *******/
/* *********************************************************/
/* Dernière modification : le 19/01/15 *********************/
/* *********************************************************/


function makeValue($str) {
	if ($str == 'false') return false;
	if ($str == 'true') return true;
	if (is_numeric($str)) return (float) $str;
	return $str;
}

function printEtatPaiement($etat) {
	$etats = [
		'paye' => '<b style="color:green;">Payé</b>',
		'refus' => '<b style="color:red;">Refus</b>',
		'annule' => '<b style="color:red;">Annulé</b>',
		'attente' => '<b style="color:blue;">En attente</b>',
	];

	return in_array($etat, array_keys($etats)) ? 
		$etats[$etat] : '<i>Inconnu</i>';
}

function printEtatEcole($etat) {
	$etats = [
		'fermee' => '<b style="color:red;">Inscription non lancée</b>',
		'ouverte' => '<b style="color:blue;">Inscription ouverte</b>',
		'close' => '<b style="color:orange;">Inscription close</b>',
		'validee' => '<b style="color:green;">Inscription validée</b>',
	];

	return in_array($etat, array_keys($etats)) ? 
		$etats[$etat] : '<i>Inconnu</i>';
}

function printCautionEcole($etat) {
	$etats = [
		'0' => '<b style="color:red;">Caution non reçue</b>',
		'1' => '<b style="color:blue;">Caution reçue</b>',
	];

	return in_array($etat, array_keys($etats)) ? 
		$etats[$etat] : '<i>Inconnu</i>';
}

function printTypePaiement($type) {
	$types = [
		'enligne' => '<b style="color:green;">En Ligne</b>',
		'manuel' => '<b style="color:blue;">Manuel</b>',
	];

	return in_array($type, array_keys($types)) ? 
		$types[$type] : '<i>Inconnu</i>';
}

function printLogementTarif($logement) {
	$types = [
		'1' => '<b style="color:green;">Logement compris</b>',
		'0' => '<b style="color:red;">Logement non compris</b>',
	];

	return in_array($logement, array_keys($types)) ? 
		$types[$logement] : '<i>Inconnu</i>';
}

function printTypeEcole($fanfare) {
	$types = [
		'1' => '<b style="color:orange;">Fanfare</b>',
		'0' => '<b style="color:blue;">BDS</b>',
	];

	return in_array($fanfare, array_keys($types)) ? 
		$types[$fanfare] : '<i>Inconnu</i>';
}

function printSexe($sexe, $parentheses = true) {
	$sexes = [
		'm' => '<b style="color:black;">F/G</b>',
		'f' => '<b style="color:pink;">F</b>',
		'h' => '<b style="color:blue;">G</b>',
	];

	return ($parentheses ? '(' : '').
		(in_array($sexe, array_keys($sexes)) ? $sexes[$sexe] : '<i>?</i>').
		($parentheses ? ')' : '');
}

function unsecure($string) {
	return stripslashes(html_entity_decode($string));
}

function makeSheetTitle($feuille, $id) {
	static $feuilles = [];

	if ($feuille == null) return $feuilles[] = 'Feuille '.($id + 1);
	$feuille = str_replace(str_split('*:/\\?[]'), '', $feuille);
	$feuille = substr($feuille, 0, 31);
	if (!in_array($feuille, $feuilles)) return $feuilles[] = $feuille;
	$feuille = substr($feuille, 0, 29);
	$copy = 2;
	while (in_array($feuille.' '.$copy, $feuilles))
		$copy++;
	return $feuilles[] = $feuille.' '.$copy;
}

function colorChambre($chambre) {
	$i1 = ord($chambre[0]) + 1;
	$i2 = ord($chambre[1]) + 1;
	$i3 = ord($chambre[2]) + 1;
	$i4 = ord($chambre[3]) + 1;

	$l1 = abs(($i4 + $i1) * ($i2 + $i3) % 16);
	$l2 = abs(($i3 + $i1) * ($i4 + $i2) % 16);
	$l3 = abs(($i1 + $i2) * ($i3 + $i4) % 16);

	return '#'.dechex($l1).dechex($l2).dechex($l3);
}

function colorContrast($hex, $dark = '#000', $light = '#FFF') {
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3)
    	$hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    return (hexdec($hex) > 0xffffff/2) ? $dark : $light;
}

function exportXLSX($items, $fichier, $titre, $labels) {

	require_once DIR.'includes/PHPExcel/PHPExcel.php';
	$excel = new PHPExcel();
	makeSheet($excel, 0, $items, $titre, $labels);
	$excel->setActiveSheetIndex(0);

	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$fichier.'_'.date('d-m-Y_H-i').'.xlsx"');
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	$writer->save('php://output');
	exit;

}


function exportXLSXGroupe($items, $fichier, $feuilles, $titres, $labels) {

	require_once DIR.'includes/PHPExcel/PHPExcel.php';
	$excel = new PHPExcel();

	$i = 0;
	foreach ($items as $j => $items_sheet)
		makeSheet($excel, $i++, $items_sheet, $titres[$j], $labels, $feuilles[$j]);

	$excel->setActiveSheetIndex(0);

	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$fichier.'_'.date('d-m-Y_H-i').'.xlsx"');
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	$writer->save('php://output');
	exit;

}


function makeSheet(&$excel, $id, $items, $titre, $labels, $feuille = null) {
	$nbColumns = count($labels);
	$lastColumn = PHPExcel_Cell::stringFromColumnIndex($nbColumns - 1);
	
	if ($id)
		$excel->createSheet();

	$excel->setActiveSheetIndex($id)->setTitle(makeSheetTitle($feuille, $id));
	$excel->getDefaultStyle()->getFont()
	    ->setName('Arial')
	    ->setSize(12);
	//$excel->getActiveSheet()->setTitle($titre); //Max de 31 caractères
	$excel->getActiveSheet()
		->mergeCells('A1:'.$lastColumn.'1')
		->setCellValue('A1', 'Challenge, exporté le '.date('d\/m\/Y à H:i'))
		->mergeCells('A2:'.$lastColumn.'2')
		->setCellValue('A2', $titre);
	$excel->getActiveSheet()
		->getStyle('A1')
		->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()
		->getStyle('A2:'.$lastColumn.'3')
		->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()
		->getStyle('A2')
		->getFont()
		->setSize(20);
	$excel->getActiveSheet()
		->getStyle('A2:'.$lastColumn.'3')
		->getFont()
		->setBold(true);
	$excel->getActiveSheet()
	    ->getStyle('A2')
	    ->getFill()
	    ->applyFromArray(array(
            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array('rgb' => 'F79646')));
	$excel->getActiveSheet()
	    ->getStyle('A3:'.$lastColumn.'3')
	    ->getFill()
	    ->applyFromArray(array(
            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array('rgb' => '4F81BD')));
	$excel->getActiveSheet()
		->getStyle('A3:'.$lastColumn.'3')
		->getFont()
		->getColor()
		->setRGB('FFFFFF');;
	$excel->getActiveSheet()
		->getRowDimension(1)
		->setRowHeight(20);
	$excel->getActiveSheet()
		->getRowDimension(2)
		->setRowHeight(30);
	$excel->getActiveSheet()
		->getRowDimension(3)
		->setRowHeight(20);
	$excel->getActiveSheet()
		->getStyle('A1:'.$lastColumn.'3')
		->getAlignment()
		->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$numColumn = 0;
	foreach ($labels as $label => $sql) {
		$column = PHPExcel_Cell::stringFromColumnIndex($numColumn++);
		$excel->getActiveSheet()
			->setCellValue($column.'3', $label);
	}
	
	$excel->getActiveSheet()
		->getStyle('A2:'.$lastColumn.'3')
		->applyFromArray(array(
          	'borders' => array(
        		'allborders' => array(
                 	'style' => PHPExcel_Style_Border::BORDER_THIN))));;

	$i = 4;
	foreach ($items as $item) {

		$numColumn = 0;
		foreach ($labels as $label => $indexSQL) {
			$column = PHPExcel_Cell::stringFromColumnIndex($numColumn++);
			$excel->getActiveSheet()
				->setCellValue($column.$i, unsecure($item[$indexSQL]));
		}

		$excel->getActiveSheet()
			->getStyle('A'.$i.':'.$lastColumn.$i)
			->applyFromArray(array(
	          	'borders' => array(
	             	'allborders' => array(
	                  	'style' => PHPExcel_Style_Border::BORDER_THIN))));
		$excel->getActiveSheet()
			->getStyle('A'.$i.':'.$lastColumn.$i)
			->getAlignment()
			->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()
			->getRowDimension($i)
			->setRowHeight(20);
		$i++;
	}

	foreach(range('A', $lastColumn) as $col) {
	    $excel->getActiveSheet()
	        ->getColumnDimension($col)
	        ->setAutoSize(true);
	}

	$excel->getActiveSheet()
		->setSelectedCell('A1');
}

function api($post, $json_decode = true) {
	$return = http_post(URL_API_ECLAIR, $post);
	return $json_decode ? json_decode($return) : $return;
}