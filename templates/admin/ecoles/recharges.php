<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/ecoles/recharges.php ********************/
/* Template de la gestion des recharges ********************/
/* *********************************************************/
/* Dernière modification : le 13/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<h2>Liste des Recharges</h2>

				<?php
				if (isset($add) ||
					isset($modify) ||
					!empty($delete)) {
				?>

				<div class="alerte alerte-<?php echo !empty($add) || !empty($modify) || !empty($delete) ? 'success' : 'erreur'; ?>">
					<div class="alerte-contenu">
						<?php
						if (!empty($modify)) echo 'La recharge a bien été éditée';
						else if (!empty($delete)) echo 'La recharge a bien été supprimée';
						else if (!empty($add)) echo 'La recharge a bien été ajoutée';
						?>
					</div>
				</div>

				<?php } ?>

				<form method="post">
				<table class="table-small">
					<thead>
						<tr class="form">
							<td><input type="text" name="nom[]" placeholder="Nom..." /></td>
							<td><input type="number" step="any" min="0" name="montant[]" placeholder="Montant..." /></td>
							<td class="actions">
								<button type="submit" name="add">
									<img src="<?php url('assets/images/actions/add.png'); ?>" alt="Add" />
								</button>

								<input type="hidden" name="id[]" />
							</td>
						</tr>


						<tr>
							<th>Nom</th>
							<th>Montant</th>
							<th class="actions">Actions</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($recharges)) { ?> 

						<tr class="vide">
							<td colspan="3">Aucune recharge</td>
						</tr>

						<?php } foreach ($recharges as $recharge) { ?>

						<tr class="form">
							<td><input type="text" name="nom[]" value="<?php echo stripslashes($recharge['nom']); ?>" /></td>
							<td><input type="number" step="any" min="0" name="montant[]" value="<?php echo sprintf('%.2f', (float) $recharge['montant']); ?>" /></td>
							<td class="actions">
								<button type="submit" name="edit" value="<?php echo stripslashes($recharge['id']); ?>">
									<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Edit" />
								</button>
																
								<?php if (empty($recharge['pid'])) { ?>

								<button type="submit" name="delete" value="<?php echo stripslashes($recharge['id']); ?>" />
									<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
								</button>

								<?php } ?>

								<input type="hidden" name="id[]" value="<?php echo stripslashes($recharge['id']); ?>" />
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
			  				$nom = $first.children('input');
			  				$montant = $first.next().children('input');

			                if (!$nom.val().trim())
			                	$nom.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$.isNumeric($montant.val()) ||
			                	$montant.val() < 0)
			                	$montant.addClass('form-error').removeClass('form-error', $speed).focus();


			                if ($nom.val().trim() &&
			                	$.isNumeric($montant.val()) &&
			                	$montant.val() >= 0)
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
