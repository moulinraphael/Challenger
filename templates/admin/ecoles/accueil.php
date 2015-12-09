<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/ecoles/accueil.php **********************/
/* Template de l'accueil du module des Ecoles **************/
/* *********************************************************/
/* Dernière modification : le 25/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<?php if (!empty($edit)) { ?>

				<div class="alerte alerte-success">
					<div class="alerte-contenu">
						Les paramètres ont bien été mis à jour.
					</div>
				</div>

				<?php } ?>

				<form class="form-table" method="post">
					<fieldset>
						<h3>Message sur la page de login des écoles</h3>

						<label for="form-active" class="needed">
							<span>Activé</span>
							<select name="active" id="form-active">
								<option value="0"<?php if (empty($_POST['active'])) echo ' selected'; ?>>Non</option>
								<option value="1"<?php if (!empty($_POST['active'])) echo ' selected'; ?>>Oui</option>
							</select>
						</label>

						<label for="form-message" class="needed">
							<span>Message</span>
							<textarea name="message" id="form-message"><?php echo $_POST['message']; ?></textarea>
						</label>

						<center><input type="submit" class="success" name="edit" value="Editer les paramètres" /></center>
					</fieldset>
				</form>

				<script type="text/javascript">
				$(function() {
					$speed =  <?php echo APP_SPEED_ERROR; ?>;

			    	$analysis= function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			              	$parent = elem.children('fieldset');
			              	$first = $parent.children('label').first();
			  				$active = $first.children('select');
			  				$message = $first.next().children('textarea');
			  				$erreur = false;
			  				
			                if ($.inArray($active.val(), ['0', '1']) < 0) {
			                	$erreur = true;
			                	$active.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$message.val().trim() &&
			                	$active.val() == '1') {
			                	$erreur = true;
			                	$message.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($erreur)
			                	event.preventDefault();   
			           
			            }
			        };

			        $('form.form-table').first().bind('submit', function(event) { $analysis($(this), event, true); });
				});
				</script>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
