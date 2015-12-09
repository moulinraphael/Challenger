<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/configurations/liste.php ****************/
/* Template de la liste du module des constantes ***********/
/* *********************************************************/
/* Dernière modification : le 23/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<h2>Liste des Constantes</h2>

				<?php
				if (isset($add) ||
					isset($modify) ||
					!empty($delete)) {
				?>

				<div class="alerte alerte-<?php echo !empty($add) || !empty($modify) || !empty($delete) ? 'success' : 'erreur'; ?>">
					<div class="alerte-contenu">
						<?php
						if (!empty($modify)) echo 'La constante a bien été éditée';
						else if (!empty($delete)) echo 'La constante a bien été supprimée';
						else if (!empty($add)) echo 'La constante a bien été ajoutée';
						else echo 'Le flag existe déjà, veuillez en choisir un autre';
						if (!empty($add) || !empty($modify) || !empty($delete))
							echo '<br />La présente page n\'est pas soumise à cette modification.'.
								'<br /><b><a href="'.url('admin/module/configurations', false, false).'">'.
								'Rechargez la page</a></b> pour prendre en compte celle-ci.';
						?>
					</div>
				</div>

				<?php } ?>


				<form method="post">
					<table>
						<thead>

							<?php if (defined('APP_SAVE_CONSTS') && APP_SAVE_CONSTS) { ?>

							<tr class="form">
								<td><input type="text" name="flag[]" value="" placeholder="Flag..." /></td>
								<td><textarea name="nom[]" placeholder="Nom..."></textarea></td>
								<td><input type="text" name="value[]" value="" placeholder="Valeur..." /></td>
								<td class="actions">
									<button type="submit" name="add">
										<img src="<?php url('assets/images/actions/add.png'); ?>" alt="Add" />
									</button>
									<input type="hidden" name="last_flag[]" />
								</td>
							</tr>

							<?php } ?>

							<tr>
								<th>Flag</th>
								<th>Nom</th>
								<th>Valeur</th>
								<th class="actions">Actions</th>
							</tr>
						</thead>

						<tbody>

							<?php if (!count($constantes)) { ?> 

							<tr class="vide">
								<td colspan="4">Aucune constante</td>
							</tr>

							<?php } foreach ($constantes as $constante) { ?>

							<tr class="form">
								<td><input type="text" name="flag[]" value="<?php echo stripslashes($constante['flag']); ?>" /></td>
								<td><textarea name="nom[]"><?php echo stripslashes($constante['nom']); ?></textarea></td>
								<td><input type="text" name="value[]" value="<?php echo stripslashes((string) $constante['value']); ?>" /></td>
								<td class="actions">
									<button type="submit" name="edit" value="<?php echo stripslashes($constante['flag']); ?>">
										<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Edit" />
									</button>
									
									<?php if (defined('APP_SAVE_CONSTS') && APP_SAVE_CONSTS) { ?>
									
									<button type="submit" name="delete" value="<?php echo stripslashes($constante['flag']); ?>" />
										<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
									</button>

									<?php } ?>

									<input type="hidden" name="last_flag[]" value="<?php echo stripslashes($constante['flag']); ?>" />
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
			  				$flag = $first.children('input');
			  				$nom = $first.next().children('textarea');
			  				$value = $first.next().next().children('input');

			                if (!$flag.val().trim())
			                	$flag.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$nom.val().trim())
			                	$nom.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$value.val().trim())
			                	$value.addClass('form-error').removeClass('form-error', $speed).focus();

			                if ($flag.val().trim() &&
			                	$nom.val().trim() &&
			                	$value.val().trim())
			                	$parent.children('.actions').children('button:first-of-type').unbind('click').click();   
			           
			            }
			        };

					$('td input[type=text], td input[type=number], td select, td.actions button:first-of-type').bind('keypress', function(event) {
						$analysis($(this), event, false) });
					$('td.actions button:first-of-type').bind('click', function(event) {
						$analysis($(this), event, true) });	
				});
				</script>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
