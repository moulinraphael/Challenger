<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/_header.php ***********************************/
/* Haut de page ********************************************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


?>

<!DOCTYPE html>
<html>
	<head>
		<title>Challenger V3 - Gestion de l'organisation du Challenge</title>
		
		<!-- Balises Meta -->
		<meta charset="utf8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="author" content="Raphael Kichot' Moulin" />
		<meta name="description" content="Challenger V3 - Gestion de l'organisation du Challenge" />
		<meta name="keywords" content="challenge, challenger, organisation, sport, navette" />
			
		<!-- Feuilles de style CSS -->
		<link rel="stylesheet" media="all" href="<?php url('assets/css/global.css'); ?>" />
		<link rel="stylesheet" media="all" href="<?php url('assets/css/challenger.css'); ?>" />
		<link rel="stylesheet" media="print" href="<?php url('assets/css/print.css'); ?>" />

		<!-- Icones -->
		<link rel="shortcut icon" href="<?php url('assets/images/ico/favicon.ico'); ?>" type="image/x-icon" />
		<link rel="icon" href="<?php url('assets/images/ico/favicon.ico'); ?>" type="image/x-icon" />
	
		<!-- Scripts Javascript -->
		<script type="text/javascript" src="<?php url('assets/js/jquery-1.10.2.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php url('assets/js/jquery-ui-1.10.4.custom.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php url('assets/js/jquery.simplemodal.js'); ?>"></script>
	
		<?php /*<!-- BugHerd -->
		<script type='text/javascript'>
		(function (d, t) {
		  var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
		  bh.type = 'text/javascript';
		  bh.src = '//www.bugherd.com/sidebarv2.js?apikey=1x0sv8eljgnpncfcw9htma';
		  s.parentNode.insertBefore(bh, s);
		  })(document, 'script');
		</script>*/ ?>

	</head>

	<body>
		<noscript><div>Le site nécessite JavaScript pour fonctionner</div></noscript>
		<div class="container nojs">
			<header class="noprint">
				<a href="<?php url('accueil') ?>">
					Challenger 
				</a>
				<a class="presentation" target="_blank" href="<?php echo APP_URL_CHALLENGE; ?>">
					Site de présentation
				</a>
				<a class="contact" href="<?php url('contact'); ?>">
					Nous contacter
				</a>
			</header>
			