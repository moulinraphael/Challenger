<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/public/login_admin.php ************************/
/* Template affiché lors de la connexion à l'administration*/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header_nomenu.php';

?>
				<div class="login">
					<form method="post" autocomplete="off" action="<?php url('cas'); ?>">
						<fieldset>
							<legend>Connexion pour les organisateurs</legend>
							<center>
								<input type="submit" class="error" name="login_admin_cas" value="Je me connecte avec mes identifiants ECL" />
							</center>
						</fieldset>
					</form>
				</div>
					

				<div class="login">
					<form method="post" autocomplete="off">
						<fieldset>
							<legend>Connexion pour les administrateurs</legend>

							<?php if (!empty($error)) { ?>

							<div class="alerte alerte-erreur">
								<div class="alerte-contenu">
									<?php
									if ($_SESSION['tentatives']['count'] >= APP_MAX_TRY_AUTH) 
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
								<input type="submit" class="success" name="login_admin" value="Connexion" />
							</center>
						</fieldset>
					</form>
				</div>

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
