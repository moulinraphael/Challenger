<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/public/login_ecoles.php ***********************/
/* Template affiché lors de la connexion pour les écoles ***/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header_nomenu.php';

?>
				<?php
				if (!empty($_SESSION['expire'])) {
					unset($_SESSION['expire'])
				?>

				<div class="alerte alerte-erreur">
					<div class="alerte-contenu">
						Votre session a expiré après une inactivité trop longue.
					</div>
				</div>
				
				<?php } ?>

				<div class="login">
					<form method="post" action="<?php url('ecole'); ?>">
						<fieldset>
							<legend>Connexion pour les écoles</legend>
							<small>Veuillez renseigner les identifiants donnés par l'équipe Challenge.</small>

							<?php if (!empty($error) || !empty($fermee)) { ?>

							<div class="alerte alerte-erreur">
								<div class="alerte-contenu">
									<?php
									if (!empty($fermee))
										echo 'Votre inscription n\'est pas encore ouverte, '.
											'<a href="'.url('contact', false, false).'">contactez-nous</a>.';
									else if ($_SESSION['tentatives']['count'] >= APP_MAX_TRY_AUTH) 
										echo 'Trop de tentatives ont été soumises, veuillez patienter.';
									else
										echo 'Les identifiants ne sont pas corrects.';
									?>
								</div>
							</div>

							<?php } ?>

							<label for="form-login">
								<span>Identifiant</span>
								<input type="text" autocomplete="off" name="login" id="form-login" value="" />
							</label>

							<label for="form-pass">
								<span>Mot de Passe</span>
								<input type="password" autocomplete="off" name="pass" id="form-pass" value="" />
							</label>

							<center>
								<input type="submit" class="success" name="login_ecole" value="Connexion" />
							</center>
						</fieldset>
					</form>
				</div>


				<?php if (defined('APP_ACTIVE_MESSAGE') && APP_ACTIVE_MESSAGE) { ?>

				<div class="alerte alerte-info">
					<div class="alerte-contenu">
						<h3>Message de l'équipe Challenge : </h3>
						<?php echo defined('APP_MESSAGE_LOGIN') ? nl2br(APP_MESSAGE_LOGIN) : null; ?>
					</div>
				</div>
				
				<?php } ?>


				<script type="text/javascript">
				$(function() {
					$speed =  <?php echo APP_SPEED_ERROR; ?>;
			    	$analysis = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			              	$parent = elem;
			              	$login = $('#form-login');
			  				$pass = $('#form-pass');
			  				
			                if (!$login.val().trim())
			                	$login.addClass('form-error').removeClass('form-error', $speed).focus();
			                if (!$pass.val().trim())
			                	$pass.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$login.val().trim() ||
			                	!$pass.val().trim())
			                	event.preventDefault();

			           
			            }
			        };

			        $('#form-login').focus();
					$('form').bind('submit', function(event) { $analysis($(this), event, true); });
			    });
				</script>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
