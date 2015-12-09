<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/ecoles/participants.php ***********************/
/* Template de la gestion des participants *****************/
/* *********************************************************/
/* Dernière modification : le 09/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/ecoles/_header_ecoles.php';

?>
			
				<h2>Inscription des Participants</h2>

				<div class="alerte alerte-info">
					<div class="alerte-contenu">
						<h3>Quelques données et quotas</h3>
						<br />
						<table style="margin-bottom:0px">
							<tr>
								<td><center><i>Inscriptions : </i> <?php echo '<b>'.$ecole['quota_inscriptions'].'</b> / '.$ecole['quota_total']; ?></center></td>
								<td><center><i>Sportif : </i>  <?php echo '<b>'.$ecole['quota_sportif_view'].'</b> / '.$ecole['quota_sportif']; ?></center></td>
								<td></td>
							</tr>
							<tr>
								<td><center><i>Participants logés : </i> <?php echo '<b>'.($ecole['quota_filles_logees_view'] + $ecole['quota_garcons_loges_view']).'</b> '.
									($ecole['quota_logement_on'] ? '/ '.$ecole['quota_logement'] : ''); ?></center></td>
								<td><center><i>Filles logées : </i> <?php echo '<b>'.$ecole['quota_filles_logees_view'].'</b> '.
									($ecole['quota_filles_on'] ? '/ '.$ecole['quota_filles_logees'] : ''); ?></center></td>
								<td><center><i>Garcons logés : </i> <?php echo '<b>'.$ecole['quota_garcons_loges_view'].'</b> '.
									($ecole['quota_garcons_on'] ? '/ '.$ecole['quota_garcons_loges'] : ''); ?></center></td>
							</tr>

							<tr>
								<td><center><i>Pompoms : </i> <?php echo '<b>'.$ecole['quota_pompom_view'].'</b> '.
									($ecole['quota_pompom_on'] ? '/ '.$ecole['quota_pompom'] : '').
									'<br />dont <b>'.$ecole['quota_pompom_nonsportif_view'].'</b> '.
									($ecole['quota_pompom_nonsportif_on'] ? '/ '.$ecole['quota_pompom_nonsportif'] : '').' non sportifs'; ?></center></td>
								<td><center><i>Caméramans : </i> <?php echo '<b>'.$ecole['quota_cameraman_view'].'</b> '.
									($ecole['quota_cameraman_on'] ? '/ '.$ecole['quota_cameraman'] : '').
									'<br />dont <b>'.$ecole['quota_cameraman_nonsportif_view'].'</b> '.
									($ecole['quota_cameraman_nonsportif_on'] ? '/ '.$ecole['quota_cameraman_nonsportif'] : '').' non sportifs'; ?></center></td>
								<td><center><i>Fanfarons : </i> <?php echo '<b>'.$ecole['quota_fanfaron_view'].'</b>'.
									($ecole['quota_fanfaron_on'] ? '/ '.$ecole['quota_fanfaron'] : '').
									'<br />dont <b>'.$ecole['quota_fanfaron_nonsportif_view'].'</b> '.
									($ecole['quota_fanfaron_nonsportif_on'] ? '/ '.$ecole['quota_fanfaron_nonsportif'] : '').' non sportifs'; ?></center></td>
							</tr>
						</table><br />

						<small>
							<b>Attention</b> : vous ne pouvez pas supprimer une personne étant capitaine d'une équipe<br />
							<a href="<?php url('ecole/tarifs'); ?>">Cliquez ici</a> pour voir le détail des tarifs de votre école
						</small>
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
						if (!empty($modify)) echo 'Le participant a bien été édité';
						else if (!empty($delete)) echo 'Le participant a bien été supprimé';
						else if (!empty($add)) echo 'Le participant a bien été ajouté';
						?>
					</div>
				</div>

				<?php } ?>

				<?php
				if (!empty($cap_sport_special)) {
				?>

				<div class="alerte alerte-erreur">
					<div class="alerte-contenu">
						Vous essayez de changer le tarif pour un capitaine d'un sport spécial...
					</div>
				</div>

				<?php } ?>

				
				<?php if ($ecole['quota_inscriptions'] < $ecole['quota_total']) { ?>

				<form method="post" id="ajout_participant">
					<center>
						<input type="submit" class="success" value="Ajouter un participant" />
					</center>
				</form>
				<br />

				<?php } ?>

				<form method="post">
				<table>
					<thead>

					<?php if (0) {  ?>
						
						<tr class="form">
							<td><input type="text" name="nom[]" data-logeur="" placeholder="Nom..." /></td>
							<td><input type="text" name="prenom[]" placeholder="Prénom..." /></td>
							<td>
								<select name="sexe[]">
									<option value="h">G</option>
									<option value="f">F</option>
								</select>
							</td>
							<td>
								<input type="checkbox" class="checkbox-sportif" id="form-sportif" name="sportif[]" value="0" checked />
								<label for="form-sportif"></label>
							</td>
							<td>
								<input type="checkbox" id="form-pompom" name="pompom[]" value="0" />
								<label for="form-pompom"></label>
							</td>
							<td>
								<input type="checkbox" id="form-fanfaron" name="fanfaron[]" value="0" />
								<label for="form-fanfaron"></label>
							</td>
							<td><input type="text" name="licence[]" placeholder="Licence..." /></td>
							<td><input type="text" name="telephone[]" placeholder="Téléphone..." /></td>
							<td>
								<select name="tarif[]">

								</select>
							</td>
							<td>
								<select name="recharge[]">
									<?php foreach ($recharges as $id => $recharge) { ?>

									<option data-montant="<?php echo $recharge['montant']; ?>" value="<?php echo $id; ?>"><?php echo stripslashes($recharge['nom']); ?></option>

									<?php } ?>

								</select>
							</td>
							<td>
								<center><i>-</i></center>
							</td>
							<td class="actions">
								<button type="submit" name="add">
									<img src="<?php url('assets/images/actions/add.png'); ?>" alt="Add" />
								</button>

								<input type="hidden" name="id[]" />
							</td>
						</tr>

						<?php } ?>

						<tr>
							<th>Nom</th>
							<th>Prénom</th>
							<th style="width:50px">Sexe</th>
							<th style="width:50px"><small>Sportif</small></th>
							<th style="width:50px"><small>Pompom</small></th>
							<th style="width:50px"><small>Fanfaron</small></th>
							<th>Licence</th>
							<th>Téléphone</th>
							<th style="width:250px">Tarif</th>
							<th style="width:150px">Recharge</th>
							<th>Montant</th>
							<th class="actions">Actions</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($participants)) { ?> 

						<tr class="vide">
							<td colspan="12">Aucun participant</td>
						</tr>

						<?php } foreach ($participants as $participant) { ?>

						<tr class="form">
							<td><input type="text" name="nom[]" data-logeur="<?php echo stripslashes($participant['logeur']); ?>" value="<?php echo stripslashes($participant['nom']); ?>" /></td>
							<td><input type="text" name="prenom[]" value="<?php echo stripslashes($participant['prenom']); ?>" /></td>
							<td>
								<select name="sexe[]" data-last="<?php echo $participant['sexe']; ?>">
									<option value="h"<?php if ($participant['sexe'] == 'h') echo ' selected'; ?>>G</option>
									<option value="f"<?php if ($participant['sexe'] == 'f') echo ' selected'; ?>>F</option>
								</select>
							</td>
							<td>
								<input type="checkbox" <?php if ($participant['eid'] && $participant['sportif']) echo 'disabled '; ?>class="checkbox-sportif" id="form-sportif-<?php echo $participant['id']; ?>" name="sportif[]" value="<?php echo $participant['id']; ?>" <?php if ($participant['sportif']) echo 'checked '; ?>/>
								<label for="form-sportif-<?php echo $participant['id']; ?>"></label>
								
								<?php if ($participant['eid'] && $participant['sportif']) { ?>
								
								<input type="hidden" name="sportif[]" value="<?php echo $participant['id']; ?>" />
								
								<?php } ?>

							</td>
							<td>
								<input type="checkbox" id="form-pompom-<?php echo $participant['id']; ?>" name="pompom[]" value="<?php echo $participant['id']; ?>" <?php if ($participant['pompom']) echo 'checked '; ?>/>
								<label for="form-pompom-<?php echo $participant['id']; ?>"></label>
							</td>
							<td>
								<input type="checkbox" id="form-fanfaron-<?php echo $participant['id']; ?>" name="fanfaron[]" value="<?php echo $participant['id']; ?>" <?php if ($participant['fanfaron']) echo 'checked '; ?>/>
								<label for="form-fanfaron-<?php echo $participant['id']; ?>"></label>
							</td>
							<td><input type="text" name="licence[]" <?php if (!$participant['sportif']) echo 'disabled '; ?>value="<?php if ($participant['sportif']) echo stripslashes($participant['licence']); ?>" /></td>
							<td><input type="text" name="telephone[]" value="<?php echo stripslashes($participant['telephone']); ?>" /></td>
							<td>
								<select name="tarif[]" data-last-logement="<?php echo $participant['logement']; ?>">
									
								</select>
								<input type="hidden" value="<?php echo $participant['id_tarif']; ?>" />
							</td>
							<td>
								<select name="recharge[]">
									<?php foreach ($recharges as $id => $recharge) { ?>

									<option data-montant="<?php echo $recharge['montant']; ?>" value="<?php echo $id; ?>"<?php if ($participant['id_recharge'] == $id) echo ' selected'; ?>><?php echo stripslashes($recharge['nom']); ?></option>

									<?php } ?>

								</select>
							</td>
							<td>
								<center><i>-</i></center>
							</td>
							<td class="actions">
								<button type="submit" name="edit" value="<?php echo stripslashes($participant['id']); ?>">
									<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Edit" />
								</button>
																
								<?php if (empty($participant['eid'])) { ?>

								<button type="submit" name="delete" value="<?php echo stripslashes($participant['id']); ?>" />
									<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
								</button>

								<?php } ?>

								<input type="hidden" name="id[]" value="<?php echo stripslashes($participant['id']); ?>" />
							</td>
						</tr>

						<?php } ?>

					</tbody>
				</table>
				</form>

				<div id="modal-ajout-participant" class="modal big-modal">
					<form method="post">
						<fieldset>
							<legend>Ajout d'un participant</legend>

							<label for="form-nom" class="needed">
								<span>Nom</span>
								<input type="text" name="nom" id="form-nom" value="" />
							</label>

							<label for="form-prenom" class="needed">
								<span>Prénom</span>
								<input type="text" name="nom" id="form-nom" value="" />
							</label>

							<label for="form-sexe" class="needed">
								<span>Sexe</span>
								<input type="checkbox" name="sexe" id="form-sexe" value="" checked />
								<label for="form-sexe" class="sexe"></label>
							</label>

							<label for="form-telephone">
								<span>Téléphone</span>
								<input type="text" name="telephone" id="form-telephone" value="" />
							</label>

							<label for="form-email" class="needed">
								<span>Email</span>
								<input type="text" name="email" id="form-email" value="" />
							</label>

							<br />

							<label for="form-sportif">
								<span>Sportif</span>
								<input type="checkbox" name="sportif" id="form-sportif" value="" checked />
								<label for="form-sportif"></label>
							</label>

							<label for="form-licence">
								<span>Licence</span>
								<input type="text" name="licence" id="form-licence" value="" />
							</label>

							<label>
								<span>Extras</span>
								<div class="extras">
									<input type="checkbox" name="pompom" id="form-pompom" value="" />
									<label for="form-pompom" class="extras extra-pompom"></label>

									<input type="checkbox" name="cameraman" id="form-cameraman" value="" />
									<label for="form-cameraman" class="extras extra-video"></label>

									<input type="checkbox" name="fanfaron" id="form-fanfaron" value="" />
									<label for="form-fanfaron" class="extras extra-fanfaron"></label>
								</div>
							</label>

							<br />

							<label for="form-tarif" class="needed">
								<span>Tarif</span>
								<select id="form-tarif" name="tarif"/>
								</select>
							</label>

							<label for="form-recharge">
								<span>Recharge</span>
								<select id="form-recharge" name="recharge">
									<?php foreach ($recharges as $id => $recharge) { ?>

									<option data-montant="<?php echo $recharge['montant']; ?>" value="<?php echo $id; ?>"<?php if ($participant['id_recharge'] == $id) echo ' selected'; ?>><?php echo stripslashes($recharge['nom']); ?></option>

									<?php } ?>
								</select>
							</label>

							<label>
								<span>Récapitulatif</span>
								<div></div>
							</label>
							

							<center>
								<input type="submit" class="success" value="Ajouter le participant" name="add_participant" />
							</center>
						</fieldset>
					</form>
				</div>


				<script type="text/javascript">
				$(function() {
					$speed =  <?php echo APP_SPEED_ERROR; ?>;
					$tarifs = [];

					<?php 

					function cleanTarif($tarif) {
						$temp = $tarif;
						unset($temp['description']);
						return $temp;
					}

					foreach ($tarifs_groupes as $groupe => $tarifs) {
						$tarifs = array_map('cleanTarif', $tarifs);
					?>

					$tarifs['<?php echo $groupe; ?>'] = $.parseJSON('<?php echo str_replace(array('\'', '\\"'),array('\\\'', '\\\\"'), json_encode($tarifs)); ?>'); //'

					<?php } ?>

					$analysis = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			                event.preventDefault();
			              	$parent = elem.parent().parent();
			              	$first = $parent.children('td:first');
			  				$nom = $first.children('input');
			  				$prenom = $first.next().children('input');
			  				$sexe = $first.next().next().children('select');
			  				$sportif = $first.next().next().next().children('input');
			  				$pompom = $first.next().next().next().next().children('input');
			  				$fanfaron = $first.next().next().next().next().next().children('input');
			  				$licence = $first.next().next().next().next().next().next().children('input');
			  				$telephone = $first.next().next().next().next().next().next().next().children('input');
			  				$tarif = $first.next().next().next().next().next().next().next().next().children('select');
			  				$recharge = $first.next().next().next().next().next().next().next().next().next().children('select');

			                if (!$sportif.prop('checked') &&
			                	!$pompom.prop('checked') &&
			                	!$fanfaron.prop('checked')) {
			                	$sportif.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                	$pompom.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                	$fanfaron.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                $places_filles_logees = <?php echo $ecole['quota_filles_logees'] - $ecole['quota_filles_logees_view']; ?>;
			                $places_garcons_loges = <?php echo $ecole['quota_garcons_loges'] - $ecole['quota_garcons_loges_view']; ?>;
			                $places_sportif = <?php echo $ecole['quota_sportif'] - $ecole['quota_sportif_view']; ?>;
			                $places_pompom = <?php echo $ecole['quota_pompom'] - $ecole['quota_pompom_view']; ?>;
			                $places_fanfaron = <?php echo $ecole['quota_fanfaron'] - $ecole['quota_fanfaron_view']; ?>;
			                $quota_logement_on = <?php echo $ecole['quota_logement_on'] ? '1' : '0'; ?>;

			                if ($tarif.data('last-logement') &&
			                	$sexe.data('last')) {
			                	if ($sexe.data('last') == 'f') $places_filles_logees++;
			                	if ($sexe.data('last') == 'h') $places_garcons_loges++;
			                }

			                if ($sportif.data('last')) $places_sportif++;
			                if ($pompom.data('last')) $places_pompom++;
			                if ($fanfaron.data('last')) $places_fanfaron++;

			                $erreur = false;
			                if ($tarif.children('option:selected').first().data('logement') && 
			                	$quota_logement_on && (
									$sexe.val() == 'f' && 
			                		$places_filles_logees <= 0 ||
			                		$sexe.val() == 'h' && 
			                		$places_garcons_loges <= 0)) {
			                	$erreur = true;
			                	$tarif.addClass('form-error').removeClass('form-error', $speed).focus();
			                	alert('Vous n\'avez plus de quota pour le logement');
			                }

			                if ($sportif.prop('checked') &&
			                	$places_sportif <= 0 ||
			                	$pompom.prop('checked') &&
			                	$places_pompom <= 0 ||
			                	$fanfaron.prop('checked') &&
			                	$places_fanfaron <= 0) {
			                	$erreur = true;
			                	$sportif.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                	$pompom.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                	$fanfaron.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                	alert('Vous n\'avez plus de quota disponible');
			                }

			                if (!$nom.val().trim())
			                	$nom.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$prenom.val().trim())
			                	$prenom.addClass('form-error').removeClass('form-error', $speed).focus();

			                if ($.inArray($sexe.val(), ['h', 'f']) < 0)
			                	$sexe.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$licence.val().trim() &&
			                	$sportif.prop('checked'))
			                	$licence.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$tarif.html() ||
			                	!$tarif.val().trim() ||
			                	!$.isNumeric($tarif.val()))
			                	$tarif.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$recharge.val().trim() ||
			                	!$.isNumeric($recharge.val()))
			                	$recharge.addClass('form-error').removeClass('form-error', $speed).focus();


			                if (($sportif.prop('checked') ||
			                	$pompom.prop('checked') ||
			                	$fanfaron.prop('checked')) &&
								$nom.val().trim() &&
								$prenom.val().trim() && (
									$licence.val().trim() &&
			                		$sportif.prop('checked') ||
			                		!$sportif.prop('checked')) && 
								$.inArray($sexe.val(), ['h', 'f']) >= 0 &&
								$tarif.val().trim() &&
								$.isNumeric($tarif.val()) &&
			                	$recharge.val().trim() &&
			                	$.isNumeric($recharge.val()) && 
			                	!$erreur) {

			                	<?php if (!$ecole['ecole_lyonnaise']) { ?> 

			                	if (!$tarif.children('option:selected').first().data('logement')) {
				                	$logeur = '';
				                	$logeur_default = 'Nom, Adresse, Téléphone';
				                	while (!$.trim($logeur) || $logeur == $logeur_default) {
				                		$logeur = prompt('Merci de donner quelques renseignements concernant le logement de ' + 
				                			$prenom.val() + ' ' + $nom.val(),
				                			!$nom.data('logeur') ? $logeur_default : $nom.data('logeur'));
				                		if (null === $logeur)
				                			return;
				                	}

			      					$nom.parent().append('<input type="hidden" name="logeur" value="' + $logeur.replace(/(["])/g, "\\$1") + '" />');
			      				}

			                	<?php } ?>



			                	$first.parent().addClass('_tosubmit');
			                	$('tr:not(._tosubmit) td input, tr:not(._tosubmit) td select, tr:not(._tosubmit) td button').each(function() { $(this).attr('name', '');  });
			                	$parent.children('.actions').children('button:first-of-type').unbind('click').click();   
			           		
			           		}
			            }
			        };

			        $analysisModal = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			                event.preventDefault();
			              	$parent = elem.parent().parent();
			              	$first = $parent.children('label').first();
			  				$nom = $first.children('input');
			  				$prenom = $first.next().children('input');
			  				$sexe = $first.next().next().children('input');
			  				$telephone = $first.next().next().next().children('input');
			  				$email = $first.next().next().next().next().children('input');
			  				$sportif = $first.next().next().next().next().next().next().children('input');
			  				$licence = $first.next().next().next().next().next().next().next().children('input');
			  				$pompom = $first.next().next().next().next().next().next().next().next().children('div').first().children('input').first();
			  				$cameraman = $pompom.next().next();
			  				$fanfaron = $cameraman.next().next();
			  				$tarif = $first.next().next().next().next().next().next().next().next().next().next().children('select');
			  				$recharge = $first.next().next().next().next().next().next().next().next().next().next().next().children('select');

			                if (!$sportif.prop('checked') &&
			                	!$pompom.prop('checked') &&
			                	!$cameraman.prop('checked') &&
			                	!$fanfaron.prop('checked')) {
			                	$sportif.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                	$pompom.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                	$cameraman.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                	$fanfaron.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                $logement_on = <?php echo (int) $ecole['quota_logement_on']; ?>;
			                $filles_on = <?php echo (int) $ecole['quota_filles_on']; ?>;
			                $garcons_on = <?php echo (int) $ecole['quota_garcons_on']; ?>;
			                $pompom_on = <?php echo (int) $ecole['quota_pompom_on']; ?>;
			                $pompom_nonsportif_on = <?php echo (int) $ecole['quota_pompom_nonsportif_on']; ?>;
			                $cameraman_on = <?php echo (int) $ecole['quota_cameraman_on']; ?>;
			                $cameraman_nonsportif_on = <?php echo (int) $ecole['quota_cameraman_nonsportif_on']; ?>;
			                $fanfaron_on = <?php echo (int) $ecole['quota_fanfaron_on']; ?>;
			                $fanfaron_nonsportif_on = <?php echo (int) $ecole['quota_fanfaron_nonsportif_on']; ?>;

			                $places_filles_logees = <?php echo $ecole['quota_filles_logees'] - $ecole['quota_filles_logees_view']; ?>;
			                $places_garcons_loges = <?php echo $ecole['quota_garcons_loges'] - $ecole['quota_garcons_loges_view']; ?>;
			                $places_logement = <?php echo $ecole['quota_logement'] - $ecole['quota_garcons_loges_view'] - $ecole['quota_filles_logees_view']; ?>;
			                $places_sportif = <?php echo $ecole['quota_sportif'] - $ecole['quota_sportif_view']; ?>;
			                $places_pompom = <?php echo $ecole['quota_pompom'] - $ecole['quota_pompom_view']; ?>;
			                $places_cameraman = <?php echo $ecole['quota_cameraman'] - $ecole['quota_cameraman_view']; ?>;
			                $places_fanfaron = <?php echo $ecole['quota_fanfaron'] - $ecole['quota_fanfaron_view']; ?>;
							$places_pompom_nonsportif = <?php echo $ecole['quota_pompom_nonsportif'] - $ecole['quota_pompom_nonsportif_view']; ?>;
			                $places_cameraman_nonsportif = <?php echo $ecole['quota_cameraman_nonsportif'] - $ecole['quota_cameraman_nonsportif_view']; ?>;
			                $places_fanfaron_nonsportif = <?php echo $ecole['quota_fanfaron_nonsportif'] - $ecole['quota_fanfaron_nonsportif_view']; ?>;

			                if (($logement_on || $filles_on || $garcons_on) && 
			                	$tarif.data('last-logement') &&
			                	$sexe.data('last')) {
			                	if ($logement_on) $places_logement++;
			                	if ($filles_on && $sexe.data('last') == 'f') $places_filles_logees++;
			                	if ($garcons_on && $sexe.data('last') == 'h') $places_garcons_loges++;
			                }

			                if ($sportif.data('last')) $places_sportif++;
			                if ($pompom_on && $pompom.data('last')) $places_pompom++;
			                if ($cameraman_on && $cameraman.data('last')) $places_cameraman++;
			                if ($fanfaron_on && $fanfaron.data('last')) $places_fanfaron++;
			                if ($pompom_nonsportif_on && $pompom.data('last-nonsportif')) $places_pompom_nonsportif++;
			                if ($cameraman_nonsportif_on && $cameraman.data('last-nonsportif')) $places_cameraman_nonsportif++;
			                if ($fanfaron_nonsportif_on && $fanfaron.data('last-nonsportif')) $places_fanfaron_nonsportif++;


			                $erreur = false;

			                if (!$nom.val().trim()) {
			                	$erreur = true;
			                	$nom.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$prenom.val().trim()) {
			                	$erreur = true;
			                	$prenom.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$licence.val().trim() &&
			                	$sportif.prop('checked')) {
			                	$erreur = true;
			                	$licence.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$tarif.html() ||
			                	!$tarif.val().trim() ||
			                	!$.isNumeric($tarif.val())) {
			                	$erreur = true;
			                	$tarif.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$recharge.val().trim() ||
			                	!$.isNumeric($recharge.val())) {
			                	$erreur = true;
			                	$recharge.addClass('form-error').removeClass('form-error', $speed).focus();
			               	} 

			               	if ($tarif.children('option:selected').first().data('logement') && (
			                		$logement_on && $places_logement <= 0 ||
			                		$filles_on && $sexe.val() == 'f' && $places_filles_logees <= 0 ||
			                		$garcons_on && $sexe.val() == 'h' && $places_garcons_loges <= 0)) {
			                	$erreur = true;
			                	$tarif.addClass('form-error').removeClass('form-error', $speed).focus();
			                	alert('Vous n\'avez plus de quota pour le logement');
			                }

			                if ($sportif.prop('checked') &&
			                	$places_sportif <= 0) {
			                	$erreur = true;
			                	$sportif.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                	alert('Vous n\'avez plus de quota pour les sportifs');
			                }

			                if ($pompom.prop('checked') && (
			                		$pompom_on && $places_pompom <= 0 ||
			                		$pompom_nonsportif_on && !$sportif.prop('checked') && $places_pompom_nonsportif <= 0)) {
			                	$erreur = true;
			                	$pompom.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                	alert('Vous n\'avez plus de quota pour les pompoms'+($sportif.prop('checked') ? '' : ' non sportifs'));
			               	}

			               	if ($cameraman.prop('checked') && (
			                		$cameraman_on && $places_cameraman <= 0 ||
			                		$cameraman_nonsportif_on && !$sportif.prop('checked') && $places_cameraman_nonsportif <= 0)) {
			                	$erreur = true;
			                	$cameraman.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                	alert('Vous n\'avez plus de quota pour les caméramans'+($sportif.prop('checked') ? '' : ' non sportifs'));
			               	}

			               	if ($fanfaron.prop('checked') && (
			                		$fanfaron_on && $places_fanfaron <= 0 ||
			                		$fanfaron_nonsportif_on && !$sportif.prop('checked') && $places_fanfaron_nonsportif <= 0)) {
			                	$erreur = true;
			                	$fanfaron.next().addClass('form-error').removeClass('form-error', $speed).focus();
			                	alert('Vous n\'avez plus de quota pour les fanfarons'+($sportif.prop('checked') ? '' : ' non sportifs'));
			               	} 

			                if (!$erreur) {

			                	<?php if (!$ecole['ecole_lyonnaise']) { ?> 

			                	if (!$tarif.children('option:selected').first().data('logement')) {
				                	$logeur = '';
				                	$logeur_default = 'Nom, Adresse, Téléphone';
				                	while (!$.trim($logeur) || $logeur == $logeur_default) {
				                		$logeur = prompt('Merci de donner quelques renseignements concernant le logement de ' + 
				                			$prenom.val() + ' ' + $nom.val(),
				                			!$nom.data('logeur') ? $logeur_default : $nom.data('logeur'));
				                		if (null === $logeur)
				                			return;
				                	}

			      					$nom.parent().append('<input type="hidden" name="logeur" value="' + $logeur.replace(/(["])/g, "\\$1") + '" />');
			      				}

			                	<?php } ?>
			                
			                	$parent.children('center').children('input[type=submit]').unbind('click').click();   
			           		
			           		}
			            }
			        };

					$actualiseMontant = function(elem) {
						$first = elem.parent().parent().children('td').first();
						$tarif = $first.next().next().next().next().next().next().next().next().children('select').first();
						$recharge = $first.next().next().next().next().next().next().next().next().next().children('select').first();
						$texte = $first.next().next().next().next().next().next().next().next().next().next();
						
						$montant = $tarif.children('option:selected').first().data('montant') + $recharge.children('option:selected').first().data('montant');
						if ($.isNumeric($montant))
							$texte.html('<center><i>'+$montant+' €</i></center>');
						else
							$texte.html('<center><i>-</i></center>');
					};

					$actualiseMontantModal = function(elem) {
						$first = elem.parent().parent().children('label').first();
						$tarif = $first.next().next().next().next().next().next().next().next().next().next().children('select').first();
						$recharge = $first.next().next().next().next().next().next().next().next().next().next().next().children('select').first();
						$texte = $first.next().next().next().next().next().next().next().next().next().next().next().next().children('div').first();
						
						$logement = $tarif.children('option:selected').first().data('logement'); 
						$logement = ($logement == 1 ? '<?php echo printLogementTarif(1); ?>' : '<?php echo printLogementTarif(0); ?>');
						$sport_special = $tarif.children('option:selected').first().data('sportspecial');
						$sport_special = $sport_special ? '<b>'+$sport_special+'</b>' : 'aucun'; 

						$montant = $tarif.children('option:selected').first().data('montant') + $recharge.children('option:selected').first().data('montant');
						if ($.isNumeric($montant))
							$texte.html('<i>Montant total : <b>'+$montant+' €</b><br />Logement : '+$logement+'<br />Sport spécial : '+$sport_special+'</i>');
						else
							$texte.html('<i>Montant total : <b>-</b><br />Logement : <b>-</b><br />Sport spécial : <b>-</b></i>');
					};

					$actualiseTarifs = function(elem) {
						$first = elem.parent().parent().children('td').first();
						$sportif = $first.next().next().next().children('input').first();
						$pompom = $first.next().next().next().next().children('input').first();
						$fanfaron = $first.next().next().next().next().next().children('input').first();


						$type = $sportif.prop('checked') ? 'sportif' : 'nonsportif';

						$select = $first.next().next().next().next().next().next().next().next().children('select').first();
						$hidden = $first.next().next().next().next().next().next().next().next().children('input').first();
						$select.html('');

						//Les 3 cases sont décochées
						if (!$sportif.prop('checked') &&
							!$pompom.prop('checked') &&
							!$fanfaron.prop('checked')) {
							$select.append('<option value=""></option>');
						}

						else if (!$tarifs[$type] ||
							!$tarifs[$type].length)
							$select.append('<option value=""></option>');

						else {
							$.each($tarifs[$type], function(i, $tarif) {
								$select.append('<option data-logement="'+$tarif.logement+'" data-montant="'+$tarif.tarif+'" value="' + $tarif.id + '"'+
									($hidden.val() == $tarif.id ? ' selected' : '') + '>'+ 
									($tarif.logement == '1' ? '&#8962; ' : '') + $tarif.nom + '</option>');
							});
							//$select.children('option:selected').first().attr('selected', true);
						}
						
						$actualiseMontant(elem);
					};


					$actualiseTarifsModal = function(elem) {

						$first = elem.parent().parent().children('label').first();
						$sportif = $first.next().next().next().next().next().next().children('input').first();
						$pompom = $first.next().next().next().next().next().next().next().next().children('div').first().children('input').first();
						$cameraman = $pompom.next().next();
						$fanfaron = $cameraman.next().next();

						$type = $sportif.prop('checked') ? 'sportif' : 'nonsportif';

						$select = $first.next().next().next().next().next().next().next().next().next().next().children('select').first();
						$hidden = $first.next().next().next().next().next().next().next().next().next().next().children('input').first();
						$select.html('');

						$is_sportif = $sportif.prop('checked');
						$is_pompom = $pompom.prop('checked');
						$is_cameraman = $cameraman.prop('checked');
						$is_fanfaron = $fanfaron.prop('checked');

						$is_none = !$is_sportif && !$is_pompom &&
							!$is_cameraman && !$is_fanfaron;

						$select.attr('disabled', false);

						if ($is_none ||
							!$tarifs[$type] ||
							!$tarifs[$type].length) {
							$select.append('<option value=""></option>');
							$select.attr('disabled', true);
						}

						else {
							$count = 0;

							$.each($tarifs[$type], function(i, $tarif) {

								if ($is_pompom && $tarif.for_pompom == "no" ||
									!$is_pompom && $tarif.for_pompom == "yes" ||
									$is_cameraman && $tarif.for_cameraman == "no" ||
									!$is_cameraman && $tarif.for_cameraman == "yes" ||
									$is_fanfaron && $tarif.for_fanfaron == "no" ||
									!$is_fanfaron && $tarif.for_fanfaron == "yes")
									return;

								$count++;
								$select.append('<option data-sportspecial="'+($tarif.id_sport_special ? ($tarif.sport + ' (' + 
									($tarif.sexe == 'h' ? 'H' : ($tarif.sexe == 'f' ? 'F' : 'F/G'))+')') : '')+'" ' +
									'data-logement="'+$tarif.logement+'" data-montant="'+$tarif.tarif+'" value="' + $tarif.id + '"'+
									($hidden.val() == $tarif.id ? ' selected' : '') + '>'+ 
									($tarif.logement == '1' ? '&#8962; ' : '') + $tarif.nom + '</option>');
							});

							if ($count == 0) {
								$select.append('<option value=""></option>');
								$select.attr('disabled', true);
							}
						}

						$actualiseMontantModal(elem);
					};

					$initDelete = function(elem, event) {
						$parent = elem.parent().parent();
			            $first = $parent.children('td:first');
						$first.parent().addClass('_tosubmit');
			            $('tr:not(._tosubmit) td input, tr:not(._tosubmit) td select, tr:not(._tosubmit) td button').each(function() { $(this).attr('name', ''); });
			                	
					}

					$('td input[type=text], td input[type=number], td input[type=checkbox], td select, td.actions button:first-of-type').bind('keypress', function(event) {
						$analysis($(this), event, false) });
					$('td.actions button:first-of-type').bind('click', function(event) {
						$analysis($(this), event, true) });	

					$('td.actions button:not(:first-of-type)').bind('keypress', function(event) {
						$initDelete($(this), event) });
					$('td.actions button:not(:first-of-type)').bind('click', function(event) {
						$initDelete($(this), event) });	

					$('input.checkbox-sportif').bind('change', function() {
						$licence = $(this).parent().next().next().next().children('input').first();
						if ($(this).prop('checked')) 
							$licence.val($licence.attr('title')).prop('disabled', false);					
						else
							$licence.attr('title', $licence.val()).val('').prop('disabled', true);
					});

					$('tr.form td:not(:first-child) input[type=checkbox]').change(function() {
						$actualiseTarifs($(this));
					});

					$('tr.form td input.checkbox-sportif').each(function() {
						$actualiseTarifs($(this));
					});

					$('tr.form td select').each(function() {
						$actualiseMontant($(this));
					});

					$('tr.form td select').bind('change', function() {
						$actualiseMontant($(this));
					});


					document.onselectstart = function() { return false; };

					$('#modal-ajout-participant form').bind('submit', function(event) { $analysisAjout($(this), event, true); });
					$('#form-sportif').change(function() { $('#form-licence').prop('disabled', !$(this).is(':checked')); });
					$('#form-licence').prop('disabled', !$('#form-sportif').is(':checked')); 
					$('form#ajout_participant').bind('submit', function(e) { 
						e.preventDefault(); 
						$('#modal-ajout-participant').modal();
						$('#simplemodal-container').css({'height': 'calc(100% - 50px)', 'top': '20px'});
						$('#modal-ajout-participant input[type=checkbox]').each(function() {
							$actualiseTarifsModal($(this));
						});
						$('#modal-ajout-participant label > input[type=checkbox]').bind('change', function() {
							$actualiseTarifsModal($(this));
						});
						$('#modal-ajout-participant div > input[type=checkbox]').bind('change', function() {
							$actualiseTarifsModal($(this).parent());
						});
						$('#modal-ajout-participant select').each(function() {
							$actualiseMontantModal($(this));
						});
						$('#modal-ajout-participant select').bind('change', function() {
							$actualiseMontantModal($(this));
						});
						$('#modal-ajout-participant input[type=text], #modal-ajout-participant input[type=number], #modal-ajout-participant input[type=checkbox], #modal-ajout-participant select, #modal-ajout-participant input[type=submit]').bind('keypress', function(event) {
							$analysisModal($(this), event, false);
						});
						$('#modal-ajout-participant input[type=submit]').bind('click', function(event) {
							$analysisModal($(this), event, true);
						});	
					});
					
				});
				</script>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
