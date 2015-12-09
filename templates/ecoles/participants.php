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
								<td><center><i>Fanfarons : </i> <?php echo '<b>'.$ecole['quota_fanfaron_view'].'</b> '.
									($ecole['quota_fanfaron_on'] ? '/ '.$ecole['quota_fanfaron'] : '').
									'<br />dont <b>'.$ecole['quota_fanfaron_nonsportif_view'].'</b> '.
									($ecole['quota_fanfaron_nonsportif_on'] ? '/ '.$ecole['quota_fanfaron_nonsportif'] : '').' non sportifs'; ?></center></td>
							</tr>
						</table>
					</div>
				</div><br />

				<?php
				if (isset($add) ||
					isset($modify) ||
					isset($delete) ||
					!empty($error)) {
				?>

				<div class="alerte alerte-<?php echo isset($add) || isset($modify) || isset($delete) ? 'success' : 'erreur'; ?>">
					<div class="alerte-contenu">
						<?php
						if (!empty($modify)) echo 'Le participant a bien été édité';
						else if (!empty($delete)) echo 'Le participant a bien été supprimé';
						else if (!empty($add)) echo 'Le participant a bien été ajouté';
						else if ($error == 'delete_cap') echo 'Vous ne pouvez pas supprimer un capitaine';
						else if ($error == 'add_champs' || $error == 'edit_champs') echo 'Tous les champs n\'ont pas été renseignés';
						else if ($error == 'add_tarif' || $error == 'edit_tarif') echo 'Le tarif sélectionné n\'est pas valide';
						else if ($error == 'add_quotas' || $error == 'edit_quotas') echo 'Les quotas ne permettent pas de valider l\'action';
						else if ($error == 'edit_sport_special') echo 'Impossible de changer le statut d\'un capitaine de sport spécial';
						else if ($error == 'edit_cap') echo 'Impossible de changer le statut d\'un capitaine';
						else echo 'Une erreur s\'est produite';
						?>
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
						<tr>
							<th>Nom</th>
							<th>Prénom</th>
							<th style="width:50px"><small>Sexe</small></th>
							<th style="width:50px"><small>Sportif</small></th>
							<th style="width:50px"><small>P.</small></th>
							<th style="width:50px"><small>C.</small></th>
							<th style="width:50px"><small>F.</small></th>
							<th>Email</th>
							<th>Téléphone</th>
							<th style="width:200px">Tarif</th>
							<th style="width:50px"><small>Logement</small></th>
							<th style="width:50px">Montant</th>
							<th class="actions">Actions</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($participants)) { ?> 

						<tr class="vide">
							<td colspan="13">Aucun participant</td>
						</tr>

						<?php } foreach ($participants as $participant) { ?>

						<tr class="form clickme">
							<td class="content"><?php echo stripslashes($participant['nom']); ?></td>
							<td class="content"><?php echo stripslashes($participant['prenom']); ?></td>
							<td>
								<input type="checkbox" readonly <?php if ($participant['sexe'] == 'h') echo 'checked '; ?> />
								<label class="sexe"></label>
							</td>
							<td>
								<input type="checkbox" readonly <?php if ($participant['sportif']) echo 'checked '; ?>/>
								<label></label>
							</td>
							<td>
								<input type="checkbox" readonly <?php if ($participant['pompom']) echo 'checked '; ?>/>
								<label class="extra-pompom"></label>
							</td>
							<td>
								<input type="checkbox" readonly <?php if ($participant['cameraman']) echo 'checked '; ?>/>
								<label class="extra-video"></label>
							</td>
							<td>
								<input type="checkbox" readonly <?php if ($participant['fanfaron']) echo 'checked '; ?>/>
								<label class="extra-fanfaron"></label>
							</td>
							<td class="content"><?php echo stripslashes($participant['email']); ?></td>
							<td class="content"><?php echo stripslashes($participant['telephone']); ?></td>
							<td class="content">
								<?php 
								$logement = false;
								$montant = 0;
								foreach ($nom_tarifs as $tarif) {
									if ($tarif['id'] == $participant['id_tarif']) {
										echo stripslashes($tarif['nom']);
										if ($tarif['logement']) $logement = true;
										$montant += $tarif['tarif'];
									}
								} ?>
							</td>
							<td>
								<input type="checkbox" readonly <?php if ($logement) echo 'checked '; ?>/>
								<label></label>
							</td>
							<td>
								<?php 
								foreach ($recharges as $id => $recharge) {
									if ($id == $participant['id_recharge']) {
										$montant += $recharge['montant'];
									}
								} ?>
								<center><i><?php echo $montant; ?> €</i></center>
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
							</td>
						</tr>

						<?php } ?>

					</tbody>
				</table>
				</form>

				<div class="alerte alerte-info">
					<div class="alerte-contenu">
						<h3>Légende et informations</h3>
						<div class="double">
							<input type="checkbox" checked />
							<label class="sexe label-margin"></label>
							<input type="checkbox" />
							<label class="sexe label-margin"></label>
						</div>

						<div class="double">
							<input type="checkbox" checked />
							<label class="label-margin"></label>
							<input type="checkbox" />
							<label class="label-margin"></label>
						</div>
						
						<div class="extras">
							<input type="checkbox" checked />
							<label class="extra-pompom label-margin"></label>
							<input type="checkbox" checked />
							<label class="extra-video label-margin"></label>
							<input type="checkbox" checked />
							<label class="extra-fanfaron label-margin"></label>
						</div>

						<br />
						<b>Attention</b> : vous ne pouvez pas supprimer ou changer le statut d'une personne étant capitaine d'une équipe<br />
						<a href="<?php url('ecole/tarifs'); ?>">Cliquez ici</a> pour voir le détail des tarifs de votre école
					</div>
				</div><br />

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
								<input type="text" name="prenom" id="form-prenom" value="" />
							</label>

							<label for="form-sexe" class="needed">
								<span>Sexe</span>
								<input type="checkbox" name="sexe" id="form-sexe" value="sexe" checked />
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
								<input type="checkbox" name="sportif" id="form-sportif" value="sportif" <?php if ($ecole['quota_sportif'] - $ecole['quota_sportif_view'] > 0) echo 'checked'; ?> />
								<label for="form-sportif"></label>
							</label>

							<label for="form-licence">
								<span>Licence</span>
								<input type="text" name="licence" id="form-licence" value="" />
							</label>

							<label>
								<span>Extras</span>
								<div class="extras">
									<input type="checkbox" name="pompom" id="form-pompom" value="pompom" />
									<label for="form-pompom" class="extras extra-pompom"></label>

									<input type="checkbox" name="cameraman" id="form-cameraman" value="cameraman" />
									<label for="form-cameraman" class="extras extra-video"></label>

									<input type="checkbox" name="fanfaron" id="form-fanfaron" value="fanfaron" />
									<label for="form-fanfaron" class="extras extra-fanfaron"></label>
								</div>
							</label>

							<br />

							<label for="form-tarif" class="needed">
								<span>Tarif</span>
								<select id="form-tarif" name="tarif">
								</select>
								<small><a href="<?php url('ecole/tarifs'); ?>">Cliquez ici</a> pour voir le détail des tarifs de votre école</small>
							</label>

							<label for="form-recharge">
								<span>Recharge</span>
								<select id="form-recharge" name="recharge">
									<?php foreach ($recharges as $id => $recharge) { ?>

									<option data-montant="<?php echo $recharge['montant']; ?>" value="<?php echo $id; ?>"><?php echo stripslashes($recharge['nom']); ?></option>

									<?php } ?>
								</select>
							</label>

							<label>
								<span>Récapitulatif</span>
								<small style="display:inline-block; margin-left:0px;"></small>
							</label>
							

							<center>
								<input type="submit" class="success" value="Ajouter le participant" name="add_participant" />
							</center>
						</fieldset>
					</form>
				</div>


				<?php if (!empty($participant_edit)) { ?>

				<div id="modal-edit-participant" class="modal big-modal">
					<form method="post">
						<fieldset>
							<legend>Edition d'un participant</legend>

							<label for="form-nom-edit" class="needed">
								<span>Nom</span>
								<input type="text" name="nom" id="form-nom-edit" data-logeur="<?php echo stripslashes($participant_edit['logeur']); ?>" value="<?php echo stripslashes($participant_edit['nom']); ?>" />
							</label>

							<label for="form-prenom-edit" class="needed">
								<span>Prénom</span>
								<input type="text" name="prenom" id="form-prenom-edit" value="<?php echo stripslashes($participant_edit['prenom']); ?>" />
							</label>

							<label for="form-sexe-edit" class="needed">
								<span>Sexe</span>
								<input data-last="<?php echo $participant_edit['sexe']; ?>" type="checkbox" name="sexe" id="form-sexe-edit" value="sexe" <?php echo $participant_edit['sexe'] == 'h' ? 'checked ' : ''; ?>/>
								<label for="form-sexe" class="sexe"></label>
							</label>

							<label for="form-telephone-edit">
								<span>Téléphone</span>
								<input type="text" name="telephone" id="form-telephone-edit" value="<?php echo stripslashes($participant_edit['telephone']); ?>" />
							</label>

							<label for="form-email-edit" class="needed">
								<span>Email</span>
								<input type="text" name="email" id="form-email-edit" value="<?php echo stripslashes($participant_edit['email']); ?>" />
							</label>

							<br />

							<label for="form-sportif-edit">
								<span>Sportif</span>
								<input data-last="<?php echo $participant_edit['sportif']; ?>" type="checkbox" name="sportif" id="form-sportif-edit" value="sportif" <?php echo $participant_edit['sportif'] ? 'checked ' : ''; ?>/>
								<label for="form-sportif-edit"></label>
							</label>

							<label for="form-licence-edit">
								<span>Licence</span>
								<input type="text" name="licence" id="form-licence-edit" value="<?php echo stripslashes($participant_edit['licence']); ?>" />
							</label>

							<label>
								<span>Extras</span>
								<div class="extras">
									<input data-last="<?php echo $participant_edit['pompom']; ?>" type="checkbox" name="pompom" id="form-pompom-edit" value="pompom" <?php echo $participant_edit['pompom'] ? 'checked ' : ''; ?>/>
									<label for="form-pompom-edit" class="extras extra-pompom"></label>

									<input data-last="<?php echo $participant_edit['cameraman']; ?>" type="checkbox" name="cameraman" id="form-cameraman-edit" value="cameraman" <?php echo $participant_edit['cameraman'] ? 'checked ' : ''; ?>/>
									<label for="form-cameraman-edit" class="extras extra-video"></label>

									<input data-last="<?php echo $participant_edit['fanfaron']; ?>" type="checkbox" name="fanfaron" id="form-fanfaron-edit" value="fanfaron" <?php echo $participant_edit['fanfaron'] ? 'checked ' : ''; ?>/>
									<label for="form-fanfaron-edit" class="extras extra-fanfaron"></label>
								</div>
							</label>

							<br />

							<label for="form-tarif-edit" class="needed">
								<span>Tarif</span>
								<select id="form-tarif-edit" name="tarif" data-last-tarif="<?php echo $participant_edit['id_tarif']; ?>" data-last-logement="<?php echo $participant_edit['logement']; ?>">
								</select>
								<small><a href="<?php url('ecole/tarifs'); ?>">Cliquez ici</a> pour voir le détail des tarifs de votre école</small>
							</label>

							<label for="form-recharge-edit">
								<span>Recharge</span>
								<select id="form-recharge-edit" name="recharge">
									<?php foreach ($recharges as $id => $recharge) { ?>

									<option data-montant="<?php echo $recharge['montant']; ?>" value="<?php echo $id; ?>"<?php echo $participant_edit['id_recharge'] == $id ? ' selected' : ''; ?>><?php echo stripslashes($recharge['nom']); ?></option>

									<?php } ?>
								</select>
							</label>

							<label>
								<span>Récapitulatif</span>
								<small style="display:inline-block; margin-left:0px;"></small>
							</label>
							

							<center>
								<input type="hidden" value="<?php echo $participant_edit['id']; ?>" name="id" />
								<input type="submit" class="success" value="Editer le participant" name="edit_participant" />
							</center>
						</fieldset>
					</form>
				</div>

				<?php } ?>


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
			                if ($pompom_nonsportif_on && $pompom.data('last') && !$sportif.data('last')) $places_pompom_nonsportif++;
			                if ($cameraman_nonsportif_on && $cameraman.data('last') && !$sportif.data('last')) $places_cameraman_nonsportif++;
			                if ($fanfaron_nonsportif_on && $fanfaron.data('last') && !$sportif.data('last')) $places_fanfaron_nonsportif++;


			                $erreur = false;

			                if (!$nom.val().trim()) {
			                	$erreur = true;
			                	$nom.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$prenom.val().trim()) {
			                	$erreur = true;
			                	$prenom.addClass('form-error').removeClass('form-error', $speed).focus();
			                }


						    var re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
			                if (!$email.val().trim() ||
			                	!re.test($email.val())) {
			                	$erreur = true;
			                	$email.addClass('form-error').removeClass('form-error', $speed).focus();
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

					$actualiseMontantModal = function(elem) {
						$first = elem.parent().parent().children('label').first();
						$tarif = $first.next().next().next().next().next().next().next().next().next().next().children('select').first();
						$recharge = $first.next().next().next().next().next().next().next().next().next().next().next().children('select').first();
						$texte = $first.next().next().next().next().next().next().next().next().next().next().next().next().children('small').first();
						
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

					$actualiseTarifsModal = function(elem) {

						$first = elem.parent().parent().children('label').first();
						$sportif = $first.next().next().next().next().next().next().children('input').first();
						$pompom = $first.next().next().next().next().next().next().next().next().children('div').first().children('input').first();
						$cameraman = $pompom.next().next();
						$fanfaron = $cameraman.next().next();

						$type = $sportif.prop('checked') ? 'sportif' : 'nonsportif';

						$select = $first.next().next().next().next().next().next().next().next().next().next().children('select').first();
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
									($select.data('last-tarif') == $tarif.id ? ' selected' : '') + '>'+ 
									($tarif.logement == '1' ? '&#8962; ' : '') + $tarif.nom + '</option>');
							});

							if ($count == 0) {
								$select.append('<option value=""></option>');
								$select.attr('disabled', true);
							}
						}

						$actualiseMontantModal(elem);
					};

					$initPost = function(elem, event) {
						$parent = elem.parent().parent();
			            $first = $parent.children('td:first');
						$first.parent().addClass('_tosubmit');
			            $('tr:not(._tosubmit) td input, tr:not(._tosubmit) td select, tr:not(._tosubmit) td button').each(function() { $(this).attr('name', ''); });
					}

					$('td.actions button').bind('keypress', function(event) {
						$initPost($(this), event);
					});
					$('td.actions button').bind('click', function(event) {
						$initPost($(this), event);
					});	
					$('tbody tr.form td:not(.actions)').bind('click', function() {
						$(this).parent().find('td.actions button:first-of-type').unbind('click').click();
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

					<?php if (!empty($participant_edit)) { ?>

					$('#modal-edit-participant form').bind('submit', function(event) { $analysisAjout($(this), event, true); });
					$('#form-sportif-edit').change(function() { $('#form-licence-edit').prop('disabled', !$(this).is(':checked')); });
					$('#form-licence-edit').prop('disabled', !$('#form-sportif-edit').is(':checked')); 
					$('#modal-edit-participant').modal();
					$('#simplemodal-container').css({'height': 'calc(100% - 50px)', 'top': '20px'});
					$('#modal-edit-participant input[type=checkbox]').each(function() {
						$actualiseTarifsModal($(this));
					});
					$('#modal-edit-participant label > input[type=checkbox]').bind('change', function() {
						$actualiseTarifsModal($(this));
					});
					$('#modal-edit-participant div > input[type=checkbox]').bind('change', function() {
						$actualiseTarifsModal($(this).parent());
					});
					$('#modal-edit-participant select').each(function() {
						$actualiseMontantModal($(this));
					});
					$('#modal-edit-participant select').bind('change', function() {
						$actualiseMontantModal($(this));
					});
					$('#modal-edit-participant input[type=text], #modal-edit-participant input[type=checkbox], #modal-edit-participant select, #modal-edit-participant input[type=submit]').bind('keypress', function(event) {
						$analysisModal($(this), event, false);
					});
					$('#modal-edit-participant input[type=submit]').bind('click', function(event) {
						$analysisModal($(this), event, true);
					});	

					<?php } ?>
					
				});
				</script>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
