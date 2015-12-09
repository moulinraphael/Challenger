<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/public/disabled.php ***************************/
/* Template affiché lors de la maintenance *****************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>
			
			<div class="main">
				<div class="alerte alerte-erreur">
					<div class="alerte-contenu">
						<center>
							Le site est actuellement en maintenance !<br />
							Merci de revenir un peu plus tard.<br />
							<i>L'équipe du Challenge</i><br />
							<br />
							<a target="_blank" href="<?php echo APP_URL_CHALLENGE; ?>">
								<b>Voir le site de présentation</b><br />
								<img src="<?php url('assets/images/themes/challenge_alpha.png'); ?>" alt="" />
							</a>
						</center>
					</div>
				</div>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
