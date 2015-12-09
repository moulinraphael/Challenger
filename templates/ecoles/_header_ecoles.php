<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/ecoles/_header_ecoles.php *********************/
/* Haut de page de les écoles ******************************/
/* *********************************************************/
/* Dernière modification : le 21/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

			<div class="menus noprint">
				<nav>
					<ul><!--
						--><li class="logout">
							<a href="<?php url('ecole/logout'); ?>">Déconnexion</a>
						</li><!--
						
						<?php

						if (!empty($ecole['etat_inscription']) && 
							in_array($ecole['etat_inscription'], array('ouverte', 'close'))) {

						?>

						--><li>
							<a href="<?php url('ecole/accueil'); ?>">Coordonnées</a>
						</li><!--
						
						<?php if ($ecole['etat_inscription'] == 'ouverte') { ?>

						--><li>
							<a href="<?php url('ecole/participants'); ?>">Participants</a>
						</li><!--

						<?php } ?>

						--><li>
							<a href="<?php url('ecole/equipes'); ?>">Équipes</a>
						</li><!--

						--><li>
							<a href="<?php url('ecole/sportifs'); ?>">Sportifs</a>
						</li><!--

						<?php } ?>

						--><li>
							<a href="<?php url('ecole/recapitulatif'); ?>">Récapitulatif</a>
						</li><!--
					--></ul>
				</nav>
			</div>

			<div class="main">
