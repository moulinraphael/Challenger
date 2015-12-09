<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/competition/sports.php ******************/
/* Template des sports de la compétition *******************/
/* *********************************************************/
/* Dernière modification : le 13/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Liste des Sports</h2>

				<?php
				if (isset($add) ||
					isset($modify) ||
					!empty($delete)) {
				?>

				<div class="alerte alerte-<?php echo !empty($add) || !empty($modify) || !empty($delete) ? 'success' : 'erreur'; ?>">
					<div class="alerte-contenu">
						<?php
						if (!empty($modify)) echo 'Le sport a bien été édité';
						else if (!empty($delete)) echo 'Le sport a bien été supprimé';
						else if (!empty($add)) echo 'Le sport a bien été ajouté';
						else echo 'Des équipes existent pour ce sport, impossible de supprimer';
						?>
					</div>
				</div>

				<?php } ?>

				<form method="post">
					<table class="table-small">
						<thead>
							<tr class="form">
								<td><input type="text" name="sport[]" placeholder="Sport..." /></td>
								<td>
									<select name="sexe[]">
										<option value="m">Mixte</option>
										<option value="h">Masculin</option>
										<option value="f">Féminin</option>
									</select>
								</td>
								<td><input type="number" min="0" data-sum="0" name="quota[]" placeholder="Quota Max..." /></td>
								<td><input type="number" min="0" data-sum="0" name="inscriptions[]" placeholder="Quota Inscriptions..." /></td>
								<td>
									<select name="respo[]">
										<option value="">Respo...</option>

										<?php foreach ($respos as $id => $respo) { ?>

										<option value="<?php echo $id; ?>"><?php 
											echo stripslashes(strtoupper($respo['nom']).' '.$respo['prenom']); ?></option>

										<?php } ?>

									</select>
								</td>
								<td>
									<input type="checkbox" id="form-multiples" name="multiples[]" value="0" />
									<label for="form-multiples"></label>
								</td>
								</td>
								<td class="actions">
									<button type="submit" name="add">
										<img src="<?php url('assets/images/actions/add.png'); ?>" alt="Add" />
									</button>
									<input type="hidden" name="id[]" />
								</td>
							</tr>

							<tr>
								<th>Sport</th>
								<th>Sexe</th>
								<th>Quota Max</th>
								<th>Quota Inscriptions</th>
								<th>Responsable</th>
								<th style="width:60px"><small>Multiples</small></th>
								<th>Actions</th>
							</tr>
						</thead>

						<tbody>

							<?php if (!count($sports)) { ?> 

							<tr class="vide">
								<td colspan="6">Aucun sport</td>
							</tr>

							<?php } foreach ($sports as $sid => $sport) { ?>

							<tr class="form">
								<td><input type="text" name="sport[]" value="<?php echo stripslashes($sport['sport']); ?>" /></td>
								<td>
									<select name="sexe[]">
										<option value="m"<?php if ($sport['sexe'] == 'm') echo ' selected'; ?>>Mixte</option>
										<option value="h"<?php if ($sport['sexe'] == 'h') echo ' selected'; ?>>Masculin</option>
										<option value="f"<?php if ($sport['sexe'] == 'f') echo ' selected'; ?>>Féminin</option>
									</select>
								</td>
								<td><input type="number" min="<?php echo (int) $sport['quota_sum']; ?>" data-sum="<?php echo (int) $sport['quota_sum']; ?>" name="quota[]" value="<?php echo (int) $sport['quota_max']; ?>"  /></td>
								<td><input type="number" min="<?php echo (int) $sport['quota_inscrip']; ?>" data-sum="<?php echo (int) $sport['quota_inscrip']; ?>" name="inscriptions[]" value="<?php echo $sport['quota_inscription']; ?>"  /></td>
								<td>
									<select name="respo[]">										
										<?php foreach ($respos as $id => $respo) { ?>

										<option <?php if ($id == $sport['id_respo']) echo 'selected '; ?>value="<?php echo $id; ?>"><?php 
											echo stripslashes(strtoupper($respo['nom']).' '.$respo['prenom']); ?></option>

										<?php } ?>

									</select>
								</td>
								<td>
									<input type="checkbox" id="form-multiples-<?php echo $sid; ?>" name="multiples[]" value="<?php echo $sid; ?>" <?php if ($sport['multiples']) echo 'checked '; ?>/>
									<label for="form-multiples-<?php echo $sid; ?>"></label>
								</td>
								<td class="actions">
									<button type="submit" name="edit" value="<?php echo (int) $sid; ?>">
										<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Edit" />
									</button>	

									<?php if (empty($sport['cid'])) { ?>

									<button type="submit" name="delete" value="<?php echo (int) $sid; ?>" />
										<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
									</button>

									<?php } ?>

									<input type="hidden" name="id[]" value="<?php echo (int) $sid; ?>" />
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
			              	$sport = $first.children('input');
			              	$sexe = $first.next().children('select');
			              	$quota = $first.next().next().children('input');
			              	$inscrip = $first.next().next().next().children('input');
			              	$respo = $first.next().next().next().next().children('select');
			              	$erreur = false;

			                if (!$sport.val()) {
			                	$erreur = true;
			                	$sport.addClass('form-error').removeClass('form-error', $speed).focus();
			               	}

			               	if ($.inArray($sexe.val(), ['h', 'f', 'm']) < 0) {
			                	$erreur = true;
			                	$sexe.addClass('form-error').removeClass('form-error', $speed).focus();
			               	}

			               	if (!$.isNumeric($respo.val()) ||
			                	$respo.val() <= 0 ||
			                	Math.floor($respo.val()) != $respo.val()) {
			                	$erreur = true;
			                	$respo.addClass('form-error').removeClass('form-error', $speed).focus();
			               	}

			               	if (!$.isNumeric($quota.val()) ||
			                	$quota.val() < parseInt($quota.data('sum')) ||
			                	Math.floor($quota.val()) != $quota.val()) {
			                	$erreur = true;
			                	$quota.addClass('form-error').removeClass('form-error', $speed).focus();
			               	}

			               	if ($inscrip.val() && (
				               		!$.isNumeric($inscrip.val()) ||
				                	$inscrip.val() < parseInt($inscrip.data('sum')) ||
				                	Math.floor($inscrip.val()) != $inscrip.val())) {
			                	$erreur = true;
			                	$inscrip.addClass('form-error').removeClass('form-error', $speed).focus();
			               	}
			                
			                if (!$erreur)
			                	$parent.children('.actions').children('button:first-of-type').unbind('click').click();   
			           
			            }
			        };

					$('td input[type=text], td input[type=number], td input[type=checkbox], td select, td.actions button:first-of-type').bind('keypress', function(event) {
						$analysis($(this), event, false) });
					$('td.actions button:first-of-type').bind('click', function(event) {
						$analysis($(this), event, true) });	
				});
				</script>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
