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
			
				<h2>Liste des Sportifs</h2>

				<?php
				if (isset($add) ||
					isset($modify) ||
					!empty($delete)) {
				?>

				<div class="alerte alerte-<?php echo !empty($add) || !empty($modify) || !empty($delete) ? 'success' : 'erreur'; ?>">
					<div class="alerte-contenu">
						<?php
						if (!empty($delete)) echo 'Le sportif a bien été supprimé';
						else if (!empty($add)) echo 'Le sportif a bien été ajouté';
						?>
					</div>
				</div>

				<?php } ?>

				<?php if (count($sports_doubles) > 1 &&
					in_array_multiple(array_keys($sports_doubles), explode(',', $multiples))) { ?>

				<div class="alerte alerte-info">
					<div class="alerte-contenu">
						Un sportif ne peut intégrer qu'un seul sport sauf pour les sports 
						<?php 
						$i = 0;
						foreach ($sports_doubles as $sport) { $i++;
							if ($i == count($sports_doubles)) echo ' et ';
							else if($i > 1) echo ', ';
							echo '<b>'.$sport['sport'].' '.printSexe($sport['sexe']).'</b>';
						} ?>
					</div>
				</div>

				<?php } if (!count($equipes_sportifs)) { ?>

				<div class="alerte alerte-attention">
					<div class="alerte-contenu">
						Aucune équipe n'a encore été créée.<br />
						Dirigez-vous sur <a href="<?php url('ecole/equipes'); ?>">la page concernée</a> pour en ajouter !
					</div>
				</div>

				<?php } if (!empty($sport_special)) { ?>

				<div class="alerte alerte-erreur">
					<div class="alerte-contenu">
						Vous essayez d'ajouter un sportif dans un sport spécial demandant un tarif particulier.<br />
						Pour connaître les tarifs y donnant accès, <a href="<?php url('ecole/tarifs'); ?>">cliquez ici</a>
					</div>
				</div>

				<?php

				}

				foreach ($equipes_sportifs as $eid => $equipe) {
					
					$nb = empty($equipe[0]['pid']) ? 0 : count($equipe);
					$quota_places = $equipe[0]['effectif'] - $nb;
					$quota_inscrip = empty($equipe[0]['quota_inscription']) ? $quota_places : $equipe[0]['quota_inscription'] - $equipe[0]['inscrits'];
				
				?>

				<h3><?php echo ($equipe[0]['special'] ? '<small>Sport Spécial : </small>' : '').stripslashes($equipe[0]['sport']).' '.printSexe($equipe[0]['sexe']); ?></h3>
				<form method="post">
					<input type="hidden" name="sport" value="<?php echo $eid; ?>" />
					<table class="table-small">
						<thead>
							<tr class="form">
								<td><div><center><small><?php echo '<b>'.$nb.'</b> / '.$equipe[0]['effectif']; ?></small></div></td>
								<td colspan="2">

									<?php if (!min($quota_places, $quota_inscrip)) { ?>

									<input type="hidden" name="sportif[]" value="" />

									<?php if (!empty($equipe[0]['quota_inscription'])) { ?>

									<div>Le quota maximal d'inscriptions (<b><?php echo $equipe[0]['quota_inscription']; ?></b>) sur ce sport a été atteint</div>

									<?php } } else { ?>

									<select name="sportif[]" data-place="<?php echo min($quota_places, $quota_inscrip); ?>">
										<option value="" selected>Sportif...</option>

										<?php

										foreach ($sportifs as $sid => $sportif) {

											if ($equipe[0]['sexe'] != 'm' &&
												$equipe[0]['sexe'] != $sportif['sexe'] ||
												!empty($sportif['id_sports']) && (
													!in_array_multiple(explode(',', $sportif['id_sports']), explode(',', $multiples)) ||
													in_array($eid, explode(',', $sportif['id_sports'])) ||
													!in_array($eid, explode(',', $multiples)) ||
													array_values(explode(',', $sportif['id_sports'])) == array_values(explode(',', $multiples))) ||
												$equipe[0]['special'] &&
												$eid != $sportif['id_sport_special'])
												continue;

										?>

										<option data-sexe="<?php echo $sportif['sexe']; ?>" value="<?php echo $sid; ?>"><?php echo stripslashes(strtoupper($sportif['nom']).' '.$sportif['prenom']); ?></option>

										<?php } ?>

									</select>

									<?php } ?>

								</td>
								<td class="actions">
									
									<?php if (min($quota_places, $quota_inscrip)) { ?>

									<button type="submit" name="add">
										<img src="<?php url('assets/images/actions/add.png'); ?>" alt="Add" />
									</button>

									<?php } ?>

								</td>
							</tr>

							<tr>
								<th style="width:60px"><small>Capitaine</small></th>
								<th>Sportif</th>
								<th style="width:200px">Licence</th>
								<th class="actions">Actions</th>
							</tr>
						</thead>

						<tbody>

							<?php if (empty($equipe[0]['pid'])) { ?> 

							<tr class="vide">
								<td colspan="4">Aucun sportif</td>
							</tr>

							<?php } foreach ($equipe as $sportif) { ?>

							<tr class="form">
								<td>					
									<?php if ($equipe[0]['cid'] == $sportif['pid']) { ?>
									
									<input type="checkbox" checked />
									<label></label>

									<?php } ?>

								</td>
								<td>
									<input type="hidden" name="sportif[]" value="<?php echo $sportif['pid']; ?>" />
									<div><?php echo stripslashes(strtoupper($sportif['pnom']).' '.$sportif['pprenom']); ?></div>
								</td>
								<td>
									<div><?php echo stripslashes($sportif['plicence']); ?></div>
								</td>
								<td class="actions">
									
									<?php if ($equipe[0]['cid'] != $sportif['pid']) { ?>
									
									<button type="submit" name="delete" value="<?php echo stripslashes($sportif['pid']); ?>" />
										<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
									</button>

									<?php } ?>

								</td>
							</tr>

							<?php } ?>

						</tbody>
					</table>
				</form>

				<?php } ?>	

				<script type="text/javascript">
				$(function() {
					$speed =  <?php echo APP_SPEED_ERROR; ?>;

			    	$analysis = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			                event.preventDefault();
			              	$parent = elem.parent().parent();
			              	$first = $parent.children('td:first');
			              	$sportif = $first.next().has('select').length ? $first.next().children('select') : $first.next().children('input');

			                if (!$.isNumeric($sportif.val()) ||
			                	$sportif.val() <= 0 ||
			                	Math.floor($sportif.val()) != $sportif.val() ||
			                	$first.next().has('select').length &&
			                	$sportif.data('places') <= 0)
			                	$sportif.addClass('form-error').removeClass('form-error', $speed).focus();

			                else
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
