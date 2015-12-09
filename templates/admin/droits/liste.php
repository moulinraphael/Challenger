<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/droits/liste.php ************************/
/* Template de la liste des organisateurs ******************/
/* *********************************************************/
/* Dernière modification : le 24/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<h2>Liste des Organisateurs (CAS)</h2>

				<?php
				if (isset($add) ||
					isset($modify) ||
					!empty($delete)) {
				?>

				<div class="alerte alerte-<?php echo !empty($add) || !empty($modify) || !empty($delete) ? 'success' : 'erreur'; ?>">
					<div class="alerte-contenu">
						<?php
						if (!empty($modify)) echo 'L\'organisateur a bien été édité';
						else if (!empty($delete)) echo 'L\'organisateur a bien été supprimé';
						else if (!empty($add)) echo 'L\'organisateur a bien été ajouté';
						else echo 'Une erreur s\'est produite (le login existe déjà, ou le login est invalide)';
						?>
					</div>
				</div>

				<?php } ?>


				<form method="post">
					<table class="table-small">
						<thead>
							<tr class="form">
								<td><input type="text" name="login[]" value="" placeholder="Login..." /></td>
								<td><input type="text" name="poste[]" value="" placeholder="Poste..." /></td>
								<td>
									<input type="checkbox" id="form-contact" name="contact[]" value="0" />
									<label for="form-contact"></label>
								</td>
								<td><input type="text" name="telephone[]" value="" placeholder="Téléphone..." /></td>
								<td class="actions">
									<button type="submit" name="add">
										<img src="<?php url('assets/images/actions/add.png'); ?>" alt="Add" />
									</button>
									<input type="hidden" name="id[]" />
								</td>
							</tr>

							<tr>
								<th>Login</th>
								<th>Poste</th>
								<th style="width:60px"><small>Contact</small></th>
								<th>Téléphone</th>
								<th class="actions">Actions</th>
							</tr>
						</thead>

						<tbody>

							<?php if (!count($admins)) { ?> 

							<tr class="vide">
								<td colspan="9">Aucun organisateur</td>
							</tr>

							<?php } foreach ($admins as $admin) { ?>

							<tr class="form">
								<td><input type="text" name="login[]" value="<?php echo stripslashes($admin['login']); ?>" /></td>
								<td><input type="text" name="poste[]" value="<?php echo stripslashes($admin['poste']); ?>" /></td>
								<td>
									<input type="checkbox" id="form-contact-<?php echo $admin['id']; ?>" name="contact[]" value="<?php echo $admin['id']; ?>" <?php if ($admin['contact']) echo 'checked '; ?>/>
									<label for="form-contact-<?php echo $admin['id']; ?>"></label>
								</td>
								<td><input type="text" name="telephone[]" value="<?php echo stripslashes($admin['telephone']); ?>" /></td>
								<td class="actions">
									<button type="submit" name="edit" value="<?php echo stripslashes($admin['id']); ?>">
										<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Edit" />
									</button>									
									<button type="submit" name="delete" value="<?php echo stripslashes($admin['id']); ?>" />
										<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
									</button>
									<input type="hidden" name="id[]" value="<?php echo stripslashes($admin['id']); ?>" />
								</td>
							</tr>

							<?php } ?>

						</tbody>
					</table>
				</form>

				<script type="text/javascript">
				$(function() {
					$speed =  <?php echo APP_SPEED_ERROR; ?>;

			    	$analysis = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			                event.preventDefault();
			              	$parent = elem.parent().parent();
			              	$first = $parent.children('td:first');
			  				$login = $first.children('input');
			  				$poste = $first.next().children('input');
			  				$telephone = $first.next().next().next().children('input');

			                if (!$login.val().trim())
			                	$login.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$telephone.val().trim())
			                	$telephone.addClass('form-error').removeClass('form-error', $speed).focus();

			                if ($login.val().trim() &&
			                	$telephone.val().trim())
			                	$parent.children('.actions').children('button:first-of-type').unbind('click').click();   
			           
			            }
			        };

					$('td input[type=text], td input[type=number], td input[type=checkbox], td input[type=password], td select, td.actions button:first-of-type').bind('keypress', function(event) {
						$analysis($(this), event, false) });
					$('td.actions button:first-of-type').bind('click', function(event) {
						$analysis($(this), event, true) });	
				});
				</script>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
