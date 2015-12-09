<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/_error.php ************************************/
/* Template affiché pour les erreurs ***********************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header_nomenu.php';

?>
				<div class="alerte alerte-erreur">
					<div class="alerte-contenu">
						<center>
							Une erreur vient de se produire, sans doute êtes-vous sur une page inexistante,<br />
							ou alors vous n'avez pas les droits suffisants pour accéder à cette partie du site.<br />
							<br />
							<a href="<?php url(''); ?>">
								<b>Revenir à l'accueil</b><br />
								<img src="<?php url('assets/images/themes/challenge_alpha.png'); ?>" alt="" />
							</a>
						</center>
					</div>
				</div>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
