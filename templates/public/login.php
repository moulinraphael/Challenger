<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/public/login.php ******************************/
/* Template affiché pour le changement de module ***********/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header_nomenu.php';

?>

				<div class="login">
					<fieldset>
						<legend>Connexion à un module</legend>
					</fieldset>

					<?php if ($admin_actif) { ?>

					<form method="get" action="<?php url('admin'); ?>">
						<fieldset>
							<label for="form-login">
								<span>Administration</span>
								<input type="submit" class="success" value="Choisir ce module" />							</label>
							</label>
						</fieldset>
					</form>

					<?php } if ($ecole_actif) { ?>

					<form method="get" action="<?php url('ecole'); ?>">
						<fieldset>
							<label for="form-login">
								<span>École</span>
								<input type="submit" class="success" value="Choisir ce module" />							</label>
							</label>
						</fieldset>
					</form>

					<?php } if ($vp_actif) { ?>

					<form method="get" action="<?php url('vp'); ?>">
						<fieldset>
							<label for="form-login">
								<span>VP</span>
								<input type="submit" class="success" value="Choisir ce module" />							</label>
							</label>
						</fieldset>
					</form>

					<?php } ?>

				</div>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
