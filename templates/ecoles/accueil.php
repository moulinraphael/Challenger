<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/ecoles/accueil.php ****************************/
/* Template de l'accueil pour les écoles *******************/
/* *********************************************************/
/* Dernière modification : le 11/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/ecoles/_header_ecoles.php';

?>
			
				<?php if(!empty($_SESSION['ecole']['first'])) { ?>

				<div class="alerte alerte-info">
					<div class="alerte-contenu">
						<b>Bonjour, ceci est votre première visite sur le site du Challenger.</b><br />
						Vous pourrez sur cet espace renseignez toutes les données nécessaire pour l'inscription de votre école.<br />
						Bonne inscription et bon Challenge!
					</div>
				</div> 

				<?php }

				if($ecole['etat_inscription'] == 'close') { ?>

				<div class="alerte alerte-attention">
					<div class="alerte-contenu">
						Les inscriptions pour votre école <b>sont closes</b>. <br />
						En conséquence, vous ne pouvez plus modifier la liste de vos participants
						ainsi que leurs données. <br />Cependant il vous est toujours possible de composer les équipes et d'y placer les sportifs.
					</div>
				</div> 

				<?php } 

				if (isset($erreur_maj)) { ?>

				<div class="alerte alerte-<?php echo !empty($erreur_maj) ? 'erreur' : 'success'; ?>">
					<div class="alerte-contenu">
						<?php 
							echo !empty($erreur_maj) ? 
								'Vous n\'avez pas rempli tous les champs obligatoires' :
								'Les données ont bien été mises à jour.'; 
						?>
					</div>
				</div> 

				<?php } ?>

				<br />
				<form class="form-table" method="post">
					<fieldset>
						<h3>Coordonnées de l'école</h3>

						<label class="needed">
							<span>Nom / Type</span>
							<input class="disabled two_input" disabled type="text" value="<?php echo stripslashes($ecole['nom']); ?>" />
							<select class="disabled two_input" disabled>
								<option<?php if (!empty($ecole['ecole_lyonnaise'])) echo ' selected'; ?>>Lyonnaise</option>
								<option<?php if (empty($ecole['ecole_lyonnaise'])) echo ' selected'; ?>>Non Lyonnaise</option>
							</select>
						</label>

						<label for="form-adresse">
							<span>Adresse</span>
							<textarea name="adresse" id="form-adresse"><?php echo stripslashes($ecole['adresse']); ?></textarea>
						</label>
					
						<label for="form-code-postal">
							<span>Code Postal</span>
							<input type="text" name="code_postal" id="form-code-postal" value="<?php echo stripslashes($ecole['code_postal']); ?>" />
						</label>
					
						<label for="form-ville">
							<span>Ville</span>
							<input type="text" name="ville" id="form-ville" value="<?php echo stripslashes($ecole['ville']); ?>" />
						</label>
					
						<label for="form-email-ecole">
							<span>Email Ecole</span>
							<input type="text" name="email_ecole" id="form-email-ecole" value="<?php echo stripslashes($ecole['email_ecole']); ?>" />
						</label>
					
						<label for="form-telephone-ecole">
							<span>Téléphone Ecole</span>
							<input type="text" name="telephone_ecole" id="form-telephone-ecole" value="<?php echo stripslashes($ecole['telephone_ecole']); ?>" />
						</label>
					</fieldset>

					<fieldset>
						<h3 class="show">Responsables administratif et organisation</h3>

						<div class="bloc">
							<h3 class="hide">Responsable administratif</h3>

							<label for="form-nom-respo" class="needed">
								<span>Nom</span>
								<input type="text" name="nom_respo" id="form-nom-respo" value="<?php echo stripslashes($ecole['nom_respo']); ?>" />
							</label>

							<label for="form-prenom-respo" class="needed">
								<span>Prénom</span>
								<input type="text" name="prenom_respo" id="form-prenom-respo" value="<?php echo stripslashes($ecole['prenom_respo']); ?>" />
							</label>

							<label for="form-email-respo" class="needed">
								<span>Email</span>
								<input type="text" name="email_respo" id="form-email-respo" value="<?php echo stripslashes($ecole['email_respo']); ?>" />
							</label>

							<label for="form-telephone-respo" class="needed">
								<span>Téléphone</span>
								<input type="text" name="telephone_respo" id="form-telephone-respo" value="<?php echo stripslashes($ecole['telephone_respo']); ?>" />
							</label>
						</div>

						<div class="bloc">
							<h3 class="hide">Responsable organisation</h3>

							<label for="form-nom-corespo" class="needed">
								<span>Nom</span>
								<input type="text" name="nom_corespo" id="form-nom-corespo" value="<?php echo stripslashes($ecole['nom_corespo']); ?>" />
							</label>

							<label for="form-prenom-corespo" class="needed">
								<span>Prénom</span>
								<input type="text" name="prenom_corespo" id="form-prenom-corespo" value="<?php echo stripslashes($ecole['prenom_corespo']); ?>" />
							</label>

							<label for="form-email-corespo" class="needed">
								<span>Email</span>
								<input type="text" name="email_corespo" id="form-email-corespo" value="<?php echo stripslashes($ecole['email_corespo']); ?>" />
							</label>

							<label for="form-telephone-corespo" class="needed">
								<span>Téléphone</span>
								<input type="text" name="telephone_corespo" id="form-telephone-corespo" value="<?php echo stripslashes($ecole['telephone_corespo']); ?>" />
							</label>
						</div>
					</fieldset>

					<center>
						<input type="submit" name="save" value="Enregistrer" />
						<input type="submit" class="success" name="continue" value="Enregistrer et continuer" />
					</center>
				</form>

				<script type="text/javascript">
				$(function() {
					$speed =  <?php echo APP_SPEED_ERROR; ?>;
			    	
			    	$analysisData = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			              	$field_coords = elem.children('fieldset').first();
			              	$field_respo = $field_coords.next().children('.bloc').first();
			              	$field_corespo = $field_coords.next().children('.bloc').first().next();
			  				$first_respo = $field_respo.children('label').first();
			  				$first_corespo = $field_corespo.children('label').first();
			  				$nom_respo = $first_respo.children('input');
			  				$prenom_respo = $first_respo.next().children('input');
			  				$email_respo = $first_respo.next().next().children('input');
			  				$telephone_respo = $first_respo.next().next().next().children('input');
			  				$nom_corespo = $first_corespo.children('input');
			  				$prenom_corespo = $first_corespo.next().children('input');
			  				$email_corespo = $first_corespo.next().next().children('input');
			  				$telephone_corespo = $first_corespo.next().next().next().children('input');
			  				
			                if (!$nom_respo.val().trim())
			                	$nom_respo.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$prenom_respo.val().trim())
			                	$prenom_respo.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$email_respo.val().trim())
			                	$email_respo.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$telephone_respo.val().trim())
			                	$telephone_respo.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$nom_corespo.val().trim())
			                	$nom_corespo.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$prenom_corespo.val().trim())
			                	$prenom_corespo.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$email_corespo.val().trim())
			                	$email_corespo.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$telephone_corespo.val().trim())
			                	$telephone_corespo.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!($nom_respo.val().trim() &&
			                	$prenom_respo.val().trim() &&
			                	$email_respo.val().trim() &&
			                	$telephone_respo.val().trim() &&
			                	$nom_corespo.val().trim() &&
			                	$prenom_corespo.val().trim() && 
			                	$email_corespo.val().trim() &&
			                	$telephone_corespo.val().trim()))
			                	event.preventDefault();
			           
			            }
			        };

			        $('form.form-table').first().bind('submit', function(event) { $analysisData($(this), event, true); });
				});
				</script>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
