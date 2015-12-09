<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/ecoles/equipes.php ****************************/
/* Templates de la gestion des équipes d'une école *********/
/* *********************************************************/
/* Dernière modification : le 10/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/ecoles/_header_ecoles.php';

?>
			
				<h2>Liste des Equipes</h2>

				<div class="alerte alerte-info">
					<div class="alerte-contenu">
						<small>La création d'une équipe ajoute directement le capitaine dans celle-ci.<br />
						Le changement de capitaine, ne supprime pas l'ancien capitaine de l'équipe.<br />
						<b>Le capitaine doit avoir un téléphone !</b></small>
					</div>
				</div><br />

				<?php
				if (isset($add) ||
					isset($modify) ||
					!empty($delete)) {
				?>

				<div class="alerte alerte-<?php echo !empty($add) || !empty($modify) || !empty($delete) ? 'success' : 'erreur'; ?>">
					<div class="alerte-contenu">
						<?php
						if (!empty($modify)) echo 'L\'équipe a bien été éditée';
						else if (!empty($delete)) echo 'L\'équipe a bien été supprimée';
						else if (!empty($add)) echo 'L\'équipe a bien été ajoutée';
						?>
					</div>
				</div>

				<?php } if (!empty($sport_special)) { ?>

				<div class="alerte alerte-erreur">
					<div class="alerte-contenu">
						Vous essayez d'ajouter un sportif dans un sport spécial demandant un tarif particulier.<br />
						Pour connaître les tarifs y donnant accès, <a href="<?php url('ecole/tarifs'); ?>">cliquez ici</a>
					</div>
				</div>

				<?php }  ?>

				<form method="post">
					<table class="table-small">
						<thead>
							<tr class="form">
								<td>
									<select name="sport[]">
										<option value="" selected>Sport...</option>

										<?php foreach ($sports as $sid => $sport) { ?>

										<option data-sexe="<?php echo $sport['sexe']; ?>" data-quota="<?php echo min($sport['quota_max'],
											empty($sport['quota_inscription']) ? $sport['quota_max'] : $sport['quota_inscription'] - $sport['inscrits'] + 0); ?>" data-sportif="0" value="<?php echo $sid; ?>"><?php echo stripslashes($sport['sport']).' '.strip_tags(printSexe($sport['sexe'])); ?></option>

										<?php } ?>

									</select>
								</td>
								<td>
									<select name="capitaine[]">
										<option value="" selected>Capitaine...</option>

										<?php

										foreach ($sportifs as $sid => $sportif) {
											if (!empty($sportif['id_sports']) &&
												!in_array_multiple(explode(',', $sportif['id_sports']), explode(',', $multiples)))
												continue;
										
										?>

										<option data-sports="<?php echo $sportif['id_sports']; ?>" data-sexe="<?php echo $sportif['sexe']; ?>" value="<?php echo $sid; ?>"><?php echo stripslashes(strtoupper($sportif['nom']).' '.$sportif['prenom']); ?></option>

										<?php } ?>

									</select>
								</td>
								<td><input type="number" name="effectif[]" min="1" placeholder="Effectif..." /></td>
								<td class="vide"></td>
								<td class="actions">
									<button type="submit" name="add">
										<img src="<?php url('assets/images/actions/add.png'); ?>" alt="Add" />
									</button>
								</td>
							</tr>

							<tr>
								<th>Sport</th>
								<th>Capitaine</th>
								<th>Effectif</th>
								<th>Inscrits</th>
								<th class="actions">Actions</th>
							</tr>
						</thead>

						<tbody>

							<?php if (!count($equipes)) { ?> 

							<tr class="vide">
								<td colspan="5">Aucune équipe</td>
							</tr>

							<?php } foreach ($equipes as $eid => $equipe) { ?>

							<tr class="form">
								<td>
									<input type="hidden" name="sport[]" value="<?php echo $eid; ?>" data-sexe="<?php echo $equipe['sexe']; ?>" data-quota="<?php echo min($equipe['quota_max'],
											empty($equipe['quota_inscription']) ? $equipe['quota_max'] : $equipe['quota_inscription'] - $equipe['inscrits'] + max(1, $equipe['nb'])); ?>" data-sportifs="<?php echo max(1, $equipe['nb']); ?>" />
									<div><?php echo stripslashes($equipe['sport']).' '.printSexe($equipe['sexe']); ?></div>
								</td>
								<td>
									<select name="capitaine[]">
										<option data-sports="<?php echo $eid; ?>" data-sexe="<?php echo $equipe['sexe']; ?>" value="<?php echo $equipe['cid']; ?>" selected><?php echo stripslashes(strtoupper($equipe['cnom']).' '.$equipe['cprenom']); ?></option>

										<?php

										foreach ($sportifs as $sid => $sportif) {

											if (!empty($sportif['id_sports']) &&
												!in_array($eid, explode(',', $sportif['id_sports'])) &&
												!in_array($eid, explode(',', $multiples)) ||
												in_array($equipe['sexe'], ['h', 'f']) &&
												$sportif['sexe'] != $equipe['sexe'] ||
												$equipe['special'] &&
												$sid != $sportif['id_sport_special'])
												continue;

										?>

										<option data-sports="<?php echo $sportif['id_sports']; ?>" data-sexe="<?php echo $sportif['sexe']; ?>" value="<?php echo $sid; ?>"><?php echo stripslashes(strtoupper($sportif['nom']).' '.$sportif['prenom']); ?></option>

										<?php } ?>

									</select>
								</td>
								<td><input type="number" name="effectif[]" min="<?php echo max(1, $equipe['nb']); ?>" max="<?php echo min($equipe['quota_max'],
											empty($equipe['quota_inscription']) ? $equipe['quota_max'] : $equipe['quota_inscription'] - $equipe['inscrits'] + max(1, $equipe['nb'])); ?>" value="<?php echo $equipe['effectif']; ?>" /></td>
								<td><div><i><?php echo $equipe['nb']; ?></i></div></td>
								<td class="actions">
									<button type="submit" name="edit" value="<?php echo stripslashes($eid); ?>">
										<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Edit" />
									</button>
																		
									<button type="submit" name="delete" value="<?php echo stripslashes($eid); ?>" />
										<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
									</button>
								</td>
							</tr>

							<?php } ?>

						</tbody>
					</table>
				</form>

				<script type="text/javascript">
				$(function() {
					$speed =  <?php echo APP_SPEED_ERROR; ?>;
					$multiple_sports =  $.parseJSON('<?php echo json_encode(explode(',', $multiples)); ?>');

					function in_array_multiple(needles, haystack) {
						if (!needles.length) return false;
						needle = needles.pop();
						return $.inArray(needle, haystack) >= 0 && (
								!needles.length || 
								in_array_multiple(needles, haystack));
					}

			    	$analysis = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			                event.preventDefault();
			              	$parent = elem.parent().parent();
			              	$first = $parent.children('td:first');
			  				$sport = $first.has('select').length ? $first.children('select') : $first.children('input');
			  				$quota = $first.has('select').length ? $sport.children('option:selected').first() : $sport;
			  				$capitaine = $first.next().children('select');
			  				$effectif = $first.next().next().children('input');
			  				$sports = $capitaine.children('option:selected').first().data('sports');
			  				$sports = !$sports ? [] : ('' + $sports).split(',');
			  				$erreur = false;

			                if (!$.isNumeric($sport.val()) ||
			                	$sport.val() <= 0 ||
			                	Math.floor($sport.val()) != $sport.val()) {
			                	$erreur = true;
			                	$sport.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$.isNumeric($capitaine.val()) ||
			                	$capitaine.val() <= 0 ||
			                	Math.floor($capitaine.val()) != $capitaine.val() ||
			                	$.inArray($quota.data('sexe'), ['h', 'f']) >= 0 &&
			                	$capitaine.children('option:selected').first().data('sexe') != $quota.data('sexe') ||
			                	$sport.val() &&
			                	$sports.length && (
			                		$.inArray($sport.val(), $multiple_sports) < 0 &&
			                		$.inArray($sport.val(), $sports) < 0 ||
			                		$.inArray($sport.val(), $multiple_sports) >= 0 &&
			                		!in_array_multiple($sports, $multiple_sports))) {
			                	$erreur = true;
			                	$capitaine.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                $sportifs = parseInt($quota.data('sportifs'));
			                if (!$capitaine.children('option:selected').first().data('sports') ||
			                	$sport.val() &&
			                	$.inArray($sport.val(), $sports) < 0)
			                	$sportifs++;

			                if (!$.isNumeric($effectif.val()) ||
			                	$effectif.val() <= 0 ||
			                	Math.floor($effectif.val()) != $effectif.val() ||
			                	$effectif.val() > parseInt($quota.data('quota')) ||
			                	$effectif.val() < $sportifs) {
			                	$erreur = true;
			                	$effectif.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$erreur)
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
