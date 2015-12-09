<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/ecoles/liste.php ************************/
/* Template de la liste du module des Ecoles ***************/
/* *********************************************************/
/* Dernière modification : le 16/02/15 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<h2>Détail de l'école <i><?php echo stripslashes($ecole['nom']); ?></i></h2>

				<form method="post" autocomplete="off" class="form-table" action="#">
					<fieldset>
						<div>
							<h3>Coordonnées</h3>
							
							<?php if (isset($erreur_maj)) { ?>

							<div class="alerte alerte-<?php echo $erreur_maj === false ? 'success' : 'erreur'; ?>">
								<div class="alerte-contenu">
									<?php 
									if ($erreur_maj == 'quotas') echo 'Il y a une erreur dans les quotas'; 
									else if ($erreur_maj == 'login') echo 'Le login est déjà attribué'; 
									else if ($erreur_maj == 'pass') echo 'Les deux mots de passe ne sont pas identiques'; 
									else if ($erreur_maj == 'champs') echo 'Tous les champs n\'ont pas été correctement remplis'; 
									else if ($erreur_maj == false) echo 'Les données de l\'école ont bien été enregistrées'; 
									else echo 'Une erreur s\'est produite';
									?>
								</div>
							</div>

							<?php } ?>

							<label for="form-nom" class="needed">
								<span>Nom</span>
								<input type="text" name="nom" id="form-nom" value="<?php echo stripslashes($ecole['nom']); ?>" />
							</label>
						
							<label for="form-ecole-lyonnaise" class="needed">
								<span>Ecole Lyonnaise</span>
								<select name="ecole_lyonnaise" id="form-ecole-lyonnaise">
									<option value="0"<?php if (!$ecole['ecole_lyonnaise']) echo ' selected'; ?>>Non</option>
									<option value="1"<?php if ($ecole['ecole_lyonnaise']) echo ' selected'; ?>>Oui</option>
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
						</div>

						<div>
							<h3>Identifiants</h3>

							<label for="form-login" class="needed">
								<span>Login</span>
								<input type="text" autocomplete="off" name="login" id="form-login" value="<?php echo stripslashes($ecole['login']); ?>" />
							</label>
						
							<label for="form-pass">
								<span>Mot de Passe</span>
								<input type="password" style="display:none" /><!-- Bug Fix Auto Fill -->
								<input type="password" autocomplete="off" name="pass" id="form-pass" value="" />
							</label>
						
							<label for="form-pass-repetition">
								<span>Répétition</span>
								<input type="password" style="display:none" /><!-- Bug Fix Auto Fill -->
								<input type="password" autocomplete="off" name="pass_repetition" id="form-pass-repetition" value="" />
								<small>Complétez ces deux champs uniquement en cas de changement</small>
							</label>
						</div>

						<div>
							<h3>Quotas / Malus</h3>
							<label for="form-quota-total" class="needed">
								<span><i>Q.</i> Total</span>
								<input type="number" min="0" name="quota_total" id="form-quota-total" value="<?php echo (int) $ecole['quota_total']; ?>" />
								<small>Il y a jusqu'à présent <b><?php echo (int) $ecole['quota_inscriptions']; ?></b> inscriptions</small>
							</label>
						
							<label for="form-quota-sportif" class="needed">
								<span><i>Q.</i> Sportifs</span>
								<input type="number" min="0" name="quota_sportif" id="form-quota-sportif" value="<?php echo (int) $ecole['quota_sportif']; ?>" />
								<small>Il y a jusqu'à présent <b><?php echo (int) $ecole['quota_sportif_view']; ?></b> sportifs (donc <b><?php echo (int) $ecole['quota_inscriptions'] - (int) $ecole['quota_sportif_view']; ?></b> non sportifs)</small>
							</label>

							<label for="form-quota-logement">
								<span><i>Q.</i> Logement</span>
								<input type="checkbox" name="quota_logement_on" id="form-quota-logement-on" value="<?php echo (int) $ecole['id']; ?>" <?php if (!empty($ecole['quota_logement_on'])) echo 'checked '; ?>/>
								<label class="four_input" for="form-quota-logement-on"></label>
								<input class="fourtwo_input" type="number" min="0" name="quota_logement" id="form-quota-logement" value="<?php echo (int) $ecole['quota_logement']; ?>" />
							</label>

							<label>
								<span><i>Q.</i> Filles / Garcons</span>
								<input type="checkbox" name="quota_filles_on" id="form-quota-filles-on" value="<?php echo (int) $ecole['id']; ?>" <?php if (!empty($ecole['quota_filles_on'])) echo 'checked '; ?>/>
								<label class="four_input" for="form-quota-filles-on"></label>
								<input class="four_input" type="number" min="0" name="quota_filles_logees" id="form-quota-filles" value="<?php echo (int) $ecole['quota_filles_logees']; ?>" />
								<input type="checkbox" name="quota_garcons_on" id="form-quota-garcons-on" value="<?php echo (int) $ecole['id']; ?>" <?php if (!empty($ecole['quota_garcons_on'])) echo 'checked '; ?>/>
								<label class="four_input" for="form-quota-garcons-on"></label>
								<input class="four_input" type="number" min="0" name="quota_garcons_loges" id="form-quota-garcons" value="<?php echo (int) $ecole['quota_garcons_loges']; ?>" />
								<small>
									<div class="two_input">Il y a jusqu'à présent <b><?php echo (int) $ecole['quota_filles_logees_view']; ?></b> filles logées</div>
									<div class="two_input">Il y a jusqu'à présent <b><?php echo (int) $ecole['quota_garcons_loges_view']; ?></b> garçons logés</div>
								</small>
							</label>
							
							<label for="form-quota-pompom">
								<span><i>Q.</i> Pompoms</span>
								<input type="checkbox" name="quota_pompom_on" id="form-quota-pompom-on" value="<?php echo (int) $ecole['id']; ?>" <?php if (!empty($ecole['quota_pompom_on'])) echo 'checked '; ?>/>
								<label class="four_input" for="form-quota-pompom-on"></label>
								<input class="four_input" type="number" min="0" name="quota_pompom" id="form-quota-pompom" value="<?php echo (int) $ecole['quota_pompom']; ?>" />
								<input type="checkbox" name="quota_pompom_nonsportif_on" id="form-quota-pompom-nonsportif-on" value="<?php echo (int) $ecole['id']; ?>" <?php if (!empty($ecole['quota_pompom_nonsportif_on'])) echo 'checked '; ?>/>
								<label class="four_input" for="form-quota-pompom-nonsportif-on"></label>
								<input class="four_input" type="number" min="0" name="quota_pompom_nonsportif" id="form-quota-pompom-nonsportif" value="<?php echo (int) $ecole['quota_pompom_nonsportif']; ?>" placeholder="Pompoms non sportifs" />
								<small>
									<div class="two_input">Il y a jusqu'à présent <b><?php echo (int) $ecole['quota_pompom_view']; ?></b> pompoms.</div>
									<div class="two_input">Dont <b><?php echo (int) $ecole['quota_pompom_nonsportif_view']; ?></b> non sportifs.</div>
								</small>
							</label>

							<label for="form-quota-cameraman">
								<span><i>Q.</i> Caméramans</span>
								<input type="checkbox" name="quota_cameraman_on" id="form-quota-cameraman-on" value="<?php echo (int) $ecole['id']; ?>" <?php if (!empty($ecole['quota_cameraman_on'])) echo 'checked '; ?>/>
								<label class="four_input" for="form-quota-cameraman-on"></label>
								<input class="four_input" type="number" min="0" name="quota_cameraman" id="form-quota-cameraman" value="<?php echo (int) $ecole['quota_cameraman']; ?>" />
								<input type="checkbox" name="quota_cameraman_nonsportif_on" id="form-quota-cameraman-nonsportif-on" value="<?php echo (int) $ecole['id']; ?>" <?php if (!empty($ecole['quota_cameraman_nonsportif_on'])) echo 'checked '; ?>/>
								<label class="four_input" for="form-quota-cameraman-nonsportif-on"></label>
								<input class="four_input" type="number" min="0" name="quota_cameraman_nonsportif" id="form-quota-cameraman-nonsportif" value="<?php echo (int) $ecole['quota_cameraman_nonsportif']; ?>" placeholder="Caméramans non sportifs" />
								<small>
									<div class="two_input">Il y a jusqu'à présent <b><?php echo (int) $ecole['quota_cameraman_view']; ?></b> caméramans.</div>
									<div class="two_input">Dont <b><?php echo (int) $ecole['quota_cameraman_nonsportif_view']; ?></b> non sportifs.</div>
								</small>
							</label>
						
							<label for="form-quota-fanfaron">
								<span><i>Q.</i> Fanfarons</span>
								<input type="checkbox" name="quota_fanfaron_on" id="form-quota-fanfaron-on" value="<?php echo (int) $ecole['id']; ?>" <?php if (!empty($ecole['quota_fanfaron_on'])) echo 'checked '; ?>/>
								<label class="four_input" for="form-quota-fanfaron-on"></label>
								<input class="four_input" type="number" min="0" name="quota_fanfaron" id="form-quota-fanfaron" value="<?php echo (int) $ecole['quota_fanfaron']; ?>" />
								<input type="checkbox" name="quota_fanfaron_nonsportif_on" id="form-quota-fanfaron-nonsportif-on" value="<?php echo (int) $ecole['id']; ?>" <?php if (!empty($ecole['quota_fanfaron_nonsportif_on'])) echo 'checked '; ?>/>
								<label class="four_input" for="form-quota-fanfaron-nonsportif-on"></label>
								<input class="four_input" type="number" min="0" name="quota_fanfaron_nonsportif" id="form-quota-fanfaron-nonsportif" value="<?php echo (int) $ecole['quota_fanfaron_nonsportif']; ?>" placeholder="Fanfarons non sportifs" />
								<small>
									<div class="two_input">Il y a jusqu'à présent <b><?php echo (int) $ecole['quota_fanfaron_view']; ?></b> fanfarons.</div>
									<div class="two_input">Dont <b><?php echo (int) $ecole['quota_fanfaron_nonsportif_view']; ?></b> non sportifs.</div>
								</small>
							</label>
							
							<label for="form-malus" class="needed">
								<span>Malus %</span>
								<input type="number" step="any" min="0" name="malus" id="form-malus" value="<?php echo (float) $ecole['malus']; ?>" />
							</label>
						</div>

						<div>
							<h3>Responsable</h3>

							<label for="form-nom-respo">
								<span>Nom</span>
								<input type="text" name="nom_respo" id="form-nom-respo" value="<?php echo stripslashes($ecole['nom_respo']); ?>" />
							</label>

							<label for="form-prenom-respo">
								<span>Prénom</span>
								<input type="text" name="prenom_respo" id="form-prenom-respo" value="<?php echo stripslashes($ecole['prenom_respo']); ?>" />
							</label>

							<label for="form-email-respo">
								<span>Email</span>
								<input type="text" name="email_respo" id="form-email-respo" value="<?php echo stripslashes($ecole['email_respo']); ?>" />
							</label>

							<label for="form-telephone-respo">
								<span>Téléphone</span>
								<input type="text" name="telephone_respo" id="form-telephone-respo" value="<?php echo stripslashes($ecole['telephone_respo']); ?>" />
							</label>
						</div>

						<div>
							<h3>Co-Responsable</h3>

							<label for="form-nom-corespo">
								<span>Nom</span>
								<input type="text" name="nom_corespo" id="form-nom-corespo" value="<?php echo stripslashes($ecole['nom_corespo']); ?>" />
							</label>

							<label for="form-prenom-corespo">
								<span>Prénom</span>
								<input type="text" name="prenom_corespo" id="form-prenom-corespo" value="<?php echo stripslashes($ecole['prenom_corespo']); ?>" />
							</label>

							<label for="form-email-corespo">
								<span>Email</span>
								<input type="text" name="email_corespo" id="form-email-corespo" value="<?php echo stripslashes($ecole['email_corespo']); ?>" />
							</label>

							<label for="form-telephone-corespo">
								<span>Téléphone</span>
								<input type="text" name="telephone_corespo" id="form-telephone-corespo" value="<?php echo stripslashes($ecole['telephone_corespo']); ?>" />
							</label>
						</div>

						<center>
							<input type="submit" name="maj" value="Mettre à jour les données" class="success" />
						</center>
					</fieldset>
				</form>

				<a name="ancre-sports"></a>
				<form method="post" class="form-table" action="#ancre-sports">
					<fieldset>
						<h3>Liste des sports</h3>
					
						<?php if (isset($erreur_quotas)) { ?>

						<div class="alerte alerte-<?php echo $erreur_quotas ? 'erreur' : 'success'; ?>">
							<div class="alerte-contenu">
								<?php echo $erreur_quotas ? 'Les données saisies ne sont pas correctes' : 'Les sports de l\'école ont bien été mis à jour'; ?>
							</div>
						</div>

						<?php } ?>

						<small>Mettre le quota à 0 supprime le sport si il n'y a aucune inscription<br /> <br /></small>
						<table>
							<thead>
								<th>Nom</th>
								<th>Quota Max</th>
								<th>Effectif</th>
								<th>Inscriptions</th>
							</thead>

							<tbody>

								<?php if (!count($sports)) { ?> 

								<tr class="vide">
									<td colspan="4">Aucun sport</td>
								</tr>

								<?php } foreach ($sports as $sport) { ?>

								<tr class="form">
									<td>
										<center><?php echo stripslashes($sport['sport']).' '.printSexe($sport['sexe']); ?></center>
										<input type="hidden" name="sports[]" value="<?php echo $sport['id']; ?>" />
									</td>
									<td style="width:150px">
										<input type="number" name="quotas[]" value="<?php echo $sport['quota_max']; ?>" min="<?php echo (int) $sport['effectif']; ?>" max="<?php echo (int) $sport['quota_total'] - $sport['quota_sum']; ?>" />
									</td>
									<td><center><?php echo (int) $sport['effectif']; ?></center></td>
									<td><center><?php echo (int) $sport['inscriptions']; ?></center></td>
								</tr>

								<?php } ?>

							</tbody>
						</table>

						<center>
							<input type="submit" name="maj_sports" value="Mettre à jour les quotas" class="success" />
							<input type="button" id="form-ajout-sport" value="Ajouter un sport" />
						</center>
					</fieldset>
				</form>

				<a name="ancre-paiements"></a>
				<form method="post" id="form-change-etat" class="form-table" action="#ancre-paiements">
					<fieldset>
						<div>
							<h3>Etat de l'inscription</h3>

							<?php if (isset($edit_etat)) { ?>

							<div class="alerte alerte-success">
								<div class="alerte-contenu">
									L'état de l'inscription de l'école a bien été modifié
								</div>
							</div>

							<?php } ?>

							<label for="form-etat" class="needed">
								<span>Etat</span>
								<select name="etat">
									<option value="fermee"<?php if ($ecole['etat_inscription'] == 'fermee') echo ' selected'; ?>><?php echo strip_tags(printEtatEcole('fermee')); ?></option>
									<option value="ouverte"<?php if ($ecole['etat_inscription'] == 'ouverte') echo ' selected'; ?>><?php echo strip_tags(printEtatEcole('ouverte')); ?></option>
									<option value="close"<?php if ($ecole['etat_inscription'] == 'close') echo ' selected'; ?>><?php echo strip_tags(printEtatEcole('close')); ?></option>
									<option value="validee"<?php if ($ecole['etat_inscription'] == 'validee') echo ' selected'; ?>><?php echo strip_tags(printEtatEcole('validee')); ?></option>
								</select>
							</label>


							<label for="form-caution">
								<span>Caution reçue</span>
								<input type="checkbox" name="caution" id="form-caution" value="<?php echo (int) $ecole['id']; ?>" <?php if (!empty($ecole['caution_recue'])) echo 'checked '; ?>/>
								<label for="form-caution"></label>
							</label>


							<label for="form-reglement">
								<span>RI signé</span>
								<input type="checkbox" name="reglement" id="form-reglement" value="<?php echo (int) $ecole['id']; ?>" <?php if (!empty($ecole['ri_signe'])) echo 'checked '; ?>/>
								<label for="form-reglement"></label>
							</label>


							<label for="form-charte">
								<span>Charte acceptée</span>
								<input type="checkbox" name="charte" id="form-charte" value="<?php echo (int) $ecole['id']; ?>" <?php if (!empty($ecole['charte_acceptee'])) echo 'checked '; ?>/>
								<label for="form-charte"></label>
							</label>


							<label for="form-respo" class="needed">
								<span>Responsable</span>
								<select name="respo">
									
									<?php if (empty($ecole['id_admin'])) { ?>

									<option value="">Responsable...</option>

									<?php } foreach ($respos as $rid => $respo) { ?>

									<option value="<?php echo $rid; ?>"<?php if ($rid == $ecole['id_admin']) echo ' selected'; ?>><?php echo stripslashes(strtoupper($respo['nom']).' '.$respo['prenom']); ?></option>

									<?php } ?>

								</select>
							</label>


							<center><input class="success" type="submit" name="change_etat" value="Changer l'état" /></center><br />
						</div>

						<div>
							<h3>Etat du paiement</h3>

							Le prix à payer pour l'ensemble des participants est : <b><?php echo printMoney($montant_inscriptions['montant']); ?></b><br />
							Le prix à payer pour les recharges des participants est : <b><?php echo printMoney($montant_recharges['montant']); ?></b><br />
							<?php $montant = $montant_inscriptions['montant'] + $montant_recharges['montant']; ?>
							Inscriptions en retard (après <?php echo printDateTime(APP_DATE_MALUS, false); ?>) : <b><?php echo $inscriptions_enretard['nbretards']; ?></b><br />
							Malus pour ces inscriptions (+<?php echo (float) $ecole['malus']; ?> %) est : <b><?php echo printMoney($malus = (float) $ecole['malus'] / 100 *  $inscriptions_enretard['montant']); ?></b>
							<br />
							<br />
							Montant total : <b><?php echo printMoney($montant + $malus); ?></b><br />
							Montant payé : <b><?php echo printMoney($montant_paye['montant']); ?></b><br />
							Montant restant à payer : <b><?php echo printMoney($montant + $malus - $montant_paye['montant']); ?></b>

						
							<h3>Liste des paiements</h3>

							<?php if (isset($ajout_paiement)) { ?>

							<div class="alerte alerte-success">
								<div class="alerte-contenu">
									Le paiement a bien été ajouté
								</div>
							</div>

							<?php } ?>

							<table>
								<thead>
									<tr>
										<th>Date</th>
										<th>Type</th>
										<th>Montant</th>
										<th>Etat</th>
									</tr>
								</thead>

								<tbody>

									<?php if (!count($paiements)) { ?> 

									<tr class="vide">
										<td colspan="4">Aucun paiement</td>
									</tr>

									<?php } foreach ($paiements as $paiement) { ?>

									<tr>
										<td><?php echo printDateTime($paiement['date']); ?></td>
										<td><center><?php echo printTypePaiement($paiement['type']); ?></center></td>
										<td><center><?php echo printMoney($paiement['montant']); ?></center></td>
										<td><center><?php echo printEtatPaiement($paiement['etat']); ?></center></td>
									</tr>

									<?php } ?>

								</tbody>
							</table>

							<center>
								<input type="button" id="form-ajout-paiement" value="Ajouter un paiement" class="success" />
							</center>
						</div>
					</fieldset>
				</form>

				<form method="post" action="<?php url('admin/module/ecoles/liste'); ?>" class="form-table" onsubmit="return confirm('Etes-vous sûr de vouloir vider/supprimer cette école?');" >
					<fieldset>
						<h3>Vider l'école / Suppression de l'école</h3>
						<center>
							En vidant/supprimant l'école vous supprimerez toutes les données attachées.<br />
							À savoir : équipes, participants, paiements, ...<br />
							<b>Ces actions ne sont pas annulables !</b><br />
							<br />
							<input type="hidden" name="id" value="<?php echo $ecole['id']; ?>" />
							<input class="delete" type="submit" name="empty_ecole" value="Vider l'école de ses participants" /><br />
							<br />
							<input class="delete" type="submit" name="del_ecole" value="Supprimer l'école" />
						</center>
					</fieldset>
				</form>

				<div id="modal-ajout-paiement" class="modal">
					<form method="post" action="#ancre-paiements">
						<fieldset>
							<legend>Ajout d'un paiement manuel</legend>

							<label for="form-montant">
								<span>Montant</span>
								<input type="number" step="any" name="montant" id="form-montant" value="" />
								<small>La valeur peut être négative afin de faire une régulation</small>
							</label>

							<center>
								<input type="submit" class="success" value="Ajouter le paiement" name="add_paiement" />
							</center>
						</fieldset>
					</form>
				</div>

				<div id="modal-ajout-sport" class="modal">
					<form method="post" action="#ancre-sports">
						<fieldset>
							<legend>Ajout d'un sport</legend>

							<label for="form-sport">
								<span>Sport</span>
								<select name="sport" id="form-sport">

									<?php foreach ($sports_ajout as $sport) { ?>

									<option value="<?php echo $sport['id']; ?>" data-max="<?php echo (int) $sport['quota_total'] - $sport['quota_sum']; ?>"><?php echo strip_tags(stripslashes($sport['sport']).' '.printSexe($sport['sexe'])); ?></option>

									<?php } ?>

								</select>
							</label>

							<label for="form-quota-max">
								<span>Quota Max</span>
								<input type="number" min="1" name="quota-max" id="form-quota-max" value="" />
							</label>

							<center>
								<input type="submit" class="success" value="Ajouter le sport" name="add_sport" />
							</center>
						</fieldset>
					</form>
				</div>

				<script type="text/javascript">
				$(function() {
					$speed =  <?php echo APP_SPEED_ERROR; ?>;
			    	
			    	$analysisData = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			              	$field_coords = elem.children('fieldset').children('div').first();
			              	$field_identi = $field_coords.next();
			              	$field_quotas = $field_coords.next().next();
			  				$nom = $field_coords.children('label').first().children('input');
			  				$lyon = $field_coords.children('label').first().next().children('select');
			  				$login = $field_identi.children('label').first().children('input');
			  				$first = $field_quotas.children('label').first();
			  				$total = $first.children('input');
			  				
			  				$sportif = $first.next().children('input');
			  				$logement_on = $first.next().next().children('input').first();
			  				$logement = $logement_on.next().next();

			  				$filles_on = $first.next().next().next().children('input').first();
			  				$filles = $filles_on.next().next();
			  				$garcons_on = $filles.next();
			  				$garcons = $garcons_on.next().next();

			  				$pompom_on = $first.next().next().next().next().children('input').first();
			  				$pompom = $pompom_on.next().next();
			  				$pompom_nonsportif_on = $pompom.next();
			  				$pompom_nonsportif = $pompom_nonsportif_on.next().next();
			  				$cameraman_on = $first.next().next().next().next().next().children('input').first();
			  				$cameraman = $cameraman_on.next().next();
			  				$cameraman_nonsportif_on = $cameraman.next();
			  				$cameraman_nonsportif = $cameraman_nonsportif_on.next().next();
			  				$fanfaron_on = $first.next().next().next().next().next().next().children('input').first();
			  				$fanfaron = $fanfaron_on.next().next();
			  				$fanfaron_nonsportif_on = $fanfaron.next();
			  				$fanfaron_nonsportif = $fanfaron_nonsportif_on.next().next();
			  				$malus = $first.next().next().next().next().next().next().next().next().children('input');

			  				$erreur = false;
			  				
			                if (!$nom.val().trim()) {
			                	$erreur = true;
			                	$nom.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($.inArray($lyon.val(), ['1', '0']) < 0) {
			                	$erreur = true;
			                	$lyon.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$login.val().trim()) {
			                	$erreur = true;
			                	$login.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($filles_on.is(':checked') && (
				                	!$.isNumeric($filles.val()) ||
				                	$filles.val() < 0 ||
				                	Math.floor($filles.val()) != $filles.val() ||
				                	$filles.val() < <?php echo (int) $ecole['quota_filles_logees_view']; ?>)) {
			                	$erreur = true;
			                	$filles.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($garcons_on.is(':checked') && (
				                	!$.isNumeric($garcons.val()) ||
				                	$garcons.val() < 0 ||
				                	Math.floor($garcons.val()) != $garcons.val() ||
				                	$garcons.val() < <?php echo (int) $ecole['quota_garcons_loges_view']; ?>)) {
			                	$erreur = true;
			                	$garcons.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($logement_on.is(':checked') && (
				                	!$.isNumeric($logement.val()) ||
				                	$logement.val() < 0 ||
				                	Math.floor($logement.val()) != $logement.val() ||
				                	$logement.val() < <?php echo (int) $ecole['quota_garcons_loges_view'] + (int) $ecole['quota_filles_logees_view']; ?> ||
				                	$filles_on.is(':checked') && $garcons_on.is(':checked') && $logement.val() < parseInt($filles.val()) + parseInt($garcons.val()) ||
				                	$filles_on.is(':checked') && !$garcons_on.is(':checked') && $logement.val() < parseInt($filles.val()) ||
				                	!$filles_on.is(':checked') && $garcons_on.is(':checked') && $logement.val() < parseInt($garcons.val()))) {
			                	$erreur = true;
			                	$logement.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($pompom_nonsportif_on.is(':checked') && (
				                	!$.isNumeric($pompom_nonsportif.val()) ||
				                	$pompom_nonsportif.val() < 0 ||
				                	Math.floor($pompom_nonsportif.val()) != $pompom_nonsportif.val() ||
				                	$pompom_nonsportif.val() < <?php echo (int) $ecole['quota_pompom_nonsportif_view']; ?>)) {
			                	$erreur = true;
			                	$pompom_nonsportif.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($pompom_on.is(':checked') && (
				                	!$.isNumeric($pompom.val()) ||
				                	$pompom.val() < 0 ||
				                	Math.floor($pompom.val()) != $pompom.val() ||
				                	$pompom.val() < <?php echo (int) $ecole['quota_pompom_view']; ?> ||
				                	$pompom_nonsportif_on.is(':checked') && $pompom.val() < parseInt($pompom_nonsportif.val()))) {
			                	$erreur = true;
			                	$pompom.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($cameraman_nonsportif_on.is(':checked') && (
				                	!$.isNumeric($cameraman_nonsportif.val()) ||
				                	$cameraman_nonsportif.val() < 0 ||
				                	Math.floor($cameraman_nonsportif.val()) != $cameraman_nonsportif.val() ||
				                	$cameraman_nonsportif.val() < <?php echo (int) $ecole['quota_cameraman_nonsportif_view']; ?>)) {
			                	$erreur = true;
			                	$cameraman_nonsportif.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($cameraman_on.is(':checked') && (
				                	!$.isNumeric($cameraman.val()) ||
				                	$cameraman.val() < 0 ||
				                	Math.floor($cameraman.val()) != $cameraman.val() ||
				                	$cameraman.val() < <?php echo (int) $ecole['quota_cameraman_view']; ?> ||
				                	$cameraman_nonsportif_on.is(':checked') && $cameraman.val() < parseInt($cameraman_nonsportif.val()))) {
			                	$erreur = true;
			                	$cameraman.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($fanfaron_nonsportif_on.is(':checked') && (
				                	!$.isNumeric($fanfaron_nonsportif.val()) ||
				                	$fanfaron_nonsportif.val() < 0 ||
				                	Math.floor($fanfaron_nonsportif.val()) != $fanfaron_nonsportif.val() ||
				                	$fanfaron_nonsportif.val() < <?php echo (int) $ecole['quota_fanfaron_nonsportif_view']; ?>)) {
			                	$erreur = true;
			                	$fanfaron_nonsportif.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($fanfaron_on.is(':checked') && (
				                	!$.isNumeric($fanfaron.val()) ||
				                	$fanfaron.val() < 0 ||
				                	Math.floor($fanfaron.val()) != $fanfaron.val() ||
				                	$fanfaron.val() < <?php echo (int) $ecole['quota_fanfaron_view']; ?> ||
				                	$fanfaron_nonsportif_on.is(':checked') && $fanfaron.val() < parseInt($fanfaron_nonsportif.val()))) {
			                	$erreur = true;
			                	$fanfaron.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$.isNumeric($sportif.val()) ||
			                	$sportif.val() < 0 ||
			                	Math.floor($sportif.val()) != $sportif.val() ||
			                	$sportif.val() < <?php echo (int) $ecole['quota_sportif_view']; ?>) {
			                	$erreur = true;
			                	$sportif.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$.isNumeric($total.val()) ||
			                	$total.val() < 0 ||
			                	Math.floor($total.val()) != $total.val() ||
			                	$total.val() < <?php echo (int) $ecole['quota_inscriptions']; ?> ||
			                	$logement_on.is(':checked') && $total.val() < parseInt($logement.val()) ||
			                	$filles_on.is(':checked') && $total.val() < parseInt($filles.val()) ||
			                	$garcons_on.is(':checked') && $total.val() < parseInt($garcons.val()) ||
			                	$pompom_on.is(':checked') && $total.val() < parseInt($pompom.val()) ||
			                	$pompom_nonsportif_on.is(':checked') && $total.val() < parseInt($pompom_nonsportif.val()) ||
			                	$pompom_nonsportif_on.is(':checked') && $total.val() - parseInt($sportif.val()) < parseInt($pompom_nonsportif.val()) ||
			                	$cameraman_on.is(':checked') && $total.val() < parseInt($cameraman.val()) ||
			                	$cameraman_nonsportif_on.is(':checked') && $total.val() < parseInt($cameraman_nonsportif.val()) ||
			                	$cameraman_nonsportif_on.is(':checked') && $total.val() - parseInt($sportif.val()) < parseInt($cameraman_nonsportif.val()) ||
			                	$fanfaron_on.is(':checked') && $total.val() < parseInt($fanfaron.val()) ||
			                	$fanfaron_nonsportif_on.is(':checked') && $total.val() < parseInt($fanfaron_nonsportif.val()) ||
			                	$fanfaron_nonsportif_on.is(':checked') && $total.val() - parseInt($sportif.val()) < parseInt($fanfaron_nonsportif.val()) ||
			                	$filles_on.is(':checked') && $garcons_on.is(':checked') && $total.val() < parseInt($filles.val()) + parseInt($garcons.val()) ||
			                	$total.val() < parseInt($sportif.val())) {
			                	$erreur = true;
			                	$total.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($erreur)
			                	event.preventDefault();  

			                else {
			                	$('#form-quota-logement').prop('disabled', false);
								$('#form-quota-filles').prop('disabled', false);
								$('#form-quota-garcons').prop('disabled', false);
								$('#form-quota-pompom').prop('disabled', false);
								$('#form-quota-cameraman').prop('disabled', false);
								$('#form-quota-fanfaron').prop('disabled', false);
								$('#form-quota-pompom-nonsportif').prop('disabled', false);
								$('#form-quota-cameraman-nonsportif').prop('disabled', false);
								$('#form-quota-fanfaron-nonsportif').prop('disabled', false);
			                }
			           
			            }
			        };


			    	$analysisSport = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			              	$parent = elem.children('fieldset');
			              	$first = $parent.children('label').first();
			  				$sport = $first.children('select');
			  				$quota = $first.next().children('input');
			  				$erreur = false;
			  				
			                if (!$.isNumeric($sport.val()) ||
			                	$sport.val() <= 0 ||
			                	Math.floor($sport.val()) != $sport.val()) {
			                	$erreur = true;
			                	$sport.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$.isNumeric($quota.val()) ||
			                	$quota.val() <= 0 ||
			                	$quota.val() > $sport.children('option:selected').first().data('max') ||
			                	Math.floor($quota.val()) != $quota.val()) {
			                	$erreur = true;
			                	$quota.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($erreur)
			                	event.preventDefault();  
			           
			            }
			        };

			        $analysisPaiement = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			              	$parent = elem.children('fieldset');
			              	$first = $parent.children('label').first();
			  				$montant = $first.children('input');
			  				$erreur = false;
			  				
			                if (!$.isNumeric($montant.val())) {
			                	$erreur = true;
			                	$montant.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($erreur)
			                	event.preventDefault();   
			           
			            }
			        };

			        $analysisEtat = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			              	$parent = elem.children('fieldset').children('div');
			              	$first = $parent.children('label').first();
			  				$etat = $first.children('select');
			  				$respo = $first.next().next().next().next().children('select');
			  				$erreur = false;
			  				
			                if ($.inArray($etat.val(), ['fermee', 'ouverte', 'close', 'validee']) < 0) {
			                	$erreur = true;
			                	$etat.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($etat.val() != 'fermee' && (
				                	!$.isNumeric($respo.val()) ||
				                	$respo.val() < 0 ||
				                	Math.floor($respo.val()) != $respo.val())) {
			                	$erreur = true;
			                	$respo.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($erreur)
			                	event.preventDefault();  
			           
			            }
			        };

			        $analysisQuotas = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			              	$parents = $('fieldset table tbody tr', elem);
			              	$erreur = false;

			              	$parents.each(function(index) {
			              		$first = $(this).children('td').first();
			              		if ($first.attr('colspan') > 1)
			              			return false;
			              		$quota = $first.next().children('input');
			              		$effectif = parseInt($first.next().next().text());
			              		$inscriptions = parseInt($first.next().next().next().text());

			              		if (!$.isNumeric($quota.val()) ||
				                	$quota.val() < 0 ||
				                	$quota.val() < parseInt($effectif) && $quota.val() > 0 ||
				                	$quota.val() > parseInt($quota.attr('max')) ||
				                	$quota.val() == 0 && $inscriptions > 0) {
				                	$erreur = true;
				                	$quota.addClass('form-error').removeClass('form-error', $speed).focus();
				                }
			              	});

			                if ($erreur)
			                	event.preventDefault();  
			           
			            }
			        };

			       	$('form.form-table').first().bind('submit', function(event) { $analysisData($(this), event, true); });
			        $('form.form-table').first().next().next().bind('submit', function(event) { $analysisQuotas($(this), event, true); });
					$('#modal-ajout-paiement form').bind('submit', function(event) { $analysisPaiement($(this), event, true); });
					$('#modal-ajout-sport form').bind('submit', function(event) { $analysisSport($(this), event, true); });
					$('#form-change-etat').bind('submit', function(event) { $analysisEtat($(this), event, true); });
					$('#form-ajout-paiement').bind('click', function() { $('#modal-ajout-paiement').modal(); });
					$('#form-ajout-sport').bind('click', function() { $('#modal-ajout-sport').modal(); });

					$('#form-quota-logement-on').change(function() { $(this).next().next().prop('disabled', !$(this).is(':checked')); });
					$('#form-quota-filles-on').change(function() { $(this).next().next().prop('disabled', !$(this).is(':checked')); });
					$('#form-quota-garcons-on').change(function() { $(this).next().next().prop('disabled', !$(this).is(':checked')); });
					$('#form-quota-pompom-on').change(function() { $(this).next().next().prop('disabled', !$(this).is(':checked')); });
					$('#form-quota-cameraman-on').change(function() { $(this).next().next().prop('disabled', !$(this).is(':checked')); });
					$('#form-quota-fanfaron-on').change(function() { $(this).next().next().prop('disabled', !$(this).is(':checked')); });
					$('#form-quota-pompom-nonsportif-on').change(function() { $(this).next().next().prop('disabled', !$(this).is(':checked')); });
					$('#form-quota-cameraman-nonsportif-on').change(function() { $(this).next().next().prop('disabled', !$(this).is(':checked')); });
					$('#form-quota-fanfaron-nonsportif-on').change(function() { $(this).next().next().prop('disabled', !$(this).is(':checked')); });
					$('#form-quota-logement').prop('disabled', !$('#form-quota-logement-on').is(':checked'));
					$('#form-quota-filles').prop('disabled', !$('#form-quota-filles-on').is(':checked'));
					$('#form-quota-garcons').prop('disabled', !$('#form-quota-garcons-on').is(':checked'));
					$('#form-quota-pompom').prop('disabled', !$('#form-quota-pompom-on').is(':checked'));
					$('#form-quota-cameraman').prop('disabled', !$('#form-quota-cameraman-on').is(':checked'));
					$('#form-quota-fanfaron').prop('disabled', !$('#form-quota-fanfaron-on').is(':checked'));
					$('#form-quota-pompom-nonsportif').prop('disabled', !$('#form-quota-pompom-nonsportif-on').is(':checked'));
					$('#form-quota-cameraman-nonsportif').prop('disabled', !$('#form-quota-cameraman-nonsportif-on').is(':checked'));
					$('#form-quota-fanfaron-nonsportif').prop('disabled', !$('#form-quota-fanfaron-nonsportif-on').is(':checked'));
				});
				</script>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';

