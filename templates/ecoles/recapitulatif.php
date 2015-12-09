<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/ecoles/recapitulatif.php **********************/
/* Template pour les récapitualtifs de l'inscription *******/
/* *********************************************************/
/* Dernière modification : le 12/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/ecoles/_header_ecoles.php';

?>
				<form class="form-table" method="post">

				<fieldset>
					<div>
						<h3>Etat de l'inscription</h3>

						<label>
							<span>Etat</span>
							<?php echo printEtatEcole($ecole['etat_inscription']); ?></option>
							
							<br />
							<span>Caution</span>
							<?php echo $ecole['caution_recue'] ? 'Recue' : 'Non recue'; ?>
							
							<br />
							<span>Responsable</span>
							<div>
								
								<?php if (empty($ecole['id_admin'])) { ?>
								
								<i>Non défini</i>

								<?php } else { ?>

								<div class="clearfix contact">
									<img src="<?php echo $ecole['aauth_type'] == 'cas' ? (URL_API_ECLAIR.'?type=photo&login='.$ecole['alogin']) :
										(url('assets/images/themes/'.
										(file_exists(DIR.'assets/images/themes/'.$ecole['alogin'].'.jpg') ?
										$ecole['alogin'] : 'unknown'), false, false).'.jpg'); ?>" alt="<?php echo $ecole['alogin']; ?>" />

									<h4><?php echo stripslashes(strtoupper($ecole['anom']).' '.$ecole['aprenom']); ?></h4>
									<i><?php echo stripslashes($ecole['aposte']); ?></i><br /><br />
									<a href="mailto:<?php echo $ecole['aemail']; ?>"><?php echo $ecole['aemail']; ?></a><br />
									<a href="tel:<?php echo $ecole['atelephone']; ?>"><?php echo $ecole['atelephone']; ?></a><br />
								</div>

								<?php } ?>

							</div>
						</label>
					</div>
				</fieldset>


				<h2>Coordonnées &amp; Responsables</h2>
				<fieldset>
					<h3>Coordonnées de l'école</h3>

					<label>
						<span>Nom / <i>Type</i></span>
						<?php echo stripslashes($ecole['nom']); ?>
						 / <i><?php echo empty($ecole['ecole_lyonnaise']) ? 'Non Lyonnaise' : 'Lyonnaise'; ?></i>
						<br />

						<span>Adresse</span>
						<div><?php echo nl2br(stripslashes($ecole['adresse'])); ?></div>
						<br />

						<span>Code Postal</span>
						<?php echo stripslashes($ecole['code_postal']); ?>
						<br />

						<span>Ville</span>
						<?php echo stripslashes($ecole['ville']); ?>
						<br />

						<span>Email Ecole</span>
						<?php echo stripslashes($ecole['email_ecole']); ?>
						<br />

						<span>Téléphone Ecole</span>
						<?php echo stripslashes($ecole['telephone_ecole']); ?>
					</label>
				</fieldset>

				<fieldset>
					<h3 class="show">Responsables administratif et organisation</h3>

					<div class="bloc">
						<h3 class="hide">Responsable administratif</h3>

						<label>
							<span>Nom</span>
							<?php echo stripslashes($ecole['nom_respo']); ?>
							<br />

							<span>Prénom</span>
							<?php echo stripslashes($ecole['prenom_respo']); ?>
							<br />

							<span>Email</span>
							<?php echo stripslashes($ecole['email_respo']); ?>
							<br />

							<span>Téléphone</span>
							<?php echo stripslashes($ecole['telephone_respo']); ?>
						</label>
					</div>

					<div class="bloc">
						<h3 class="hide">Responsable organisation</h3>

						<label>
							<span>Nom</span>
							<?php echo stripslashes($ecole['nom_corespo']); ?>
							<br />

							<span>Prénom</span>
							<?php echo stripslashes($ecole['prenom_corespo']); ?>
							<br />

							<span>Email</span>
							<?php echo stripslashes($ecole['email_corespo']); ?>
							<br />

							<span>Téléphone</span>
							<?php echo stripslashes($ecole['telephone_corespo']); ?>
						</label>
					</div>
				</fieldset>


				<h2>Quotas et données numériques</h2>
				<fieldset>
					<table class="table-small" style="margin-top:20px">
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
				</fieldset>

				<h2>Participants particuliers</h2>
				<fieldset>

					<?php if (count($sans_sport)) { ?> 

					<h3>Sportifs sans sport</h3>
					<table class="table-small">
						<thead>
							<tr>
								<th>Sportif</th>
								<th style="width:200px">Licence</th>
							</tr>
						</thead>

						<tbody>

							<?php foreach ($sans_sport as $sportif) { ?>

							<tr>
								<td>
									<div><?php echo stripslashes(strtoupper($sportif['nom']).' '.$sportif['prenom']); ?></div>
								</td>
								<td>
									<div><?php echo stripslashes($sportif['licence']); ?></div>
								</td>
							</tr>

							<?php } ?>

						</tbody>
					</table>

					<?php } ?>

					<h3>Pompoms</h3>
					<table class="table-small">
						<thead>
							<tr>
								<th>Pompom</th>
							</tr>
						</thead>

						<tbody>

							<?php if (!count($pompoms)) { ?> 

							<tr class="vide">
								<td>Aucun pompom</td>
							</tr>

							<?php } foreach ($pompoms as $pompom) { ?>

							<tr>
								<td>
									<div><?php echo stripslashes(strtoupper($pompom['nom']).' '.$pompom['prenom']); ?></div>
								</td>
							</tr>

							<?php } ?>

						</tbody>
					</table>

					<h3>Fanfarons</h3>
					<table class="table-small">
						<thead>
							<tr>
								<th>Fanfaron</th>
							</tr>
						</thead>

						<tbody>

							<?php if (!count($fanfarons)) { ?> 

							<tr class="vide">
								<td>Aucun fanfaron</td>
							</tr>

							<?php } foreach ($fanfarons as $fanfaron) { ?>

							<tr>
								<td>
									<div><?php echo stripslashes(strtoupper($fanfaron['nom']).' '.$fanfaron['prenom']); ?></div>
								</td>
							</tr>

							<?php } ?>

						</tbody>
					</table>

				</fieldset>

				<h2>Equipes &amp; Sportifs</h2>
				
				<fieldset>

				<?php if (!count($equipes_sportifs)) { ?>

				<br />
				<div class="alerte alerte-attention">
					<div class="alerte-contenu">
						Aucune équipe n'a encore été créée.<br />
						Dirigez-vous sur <a href="<?php url('ecole/equipes'); ?>">la page concernée</a> pour en ajouter !
					</div>
				</div>

				<?php } foreach ($equipes_sportifs as $eid => $equipe) { ?>

					<h3><?php echo stripslashes($equipe[0]['sport']).' '.printSexe($equipe[0]['sexe']); ?></h3>
					<center>Responsable : <a href="<?php url('contact'); ?>"><b>
						<?php echo stripslashes(strtoupper($equipe[0]['anom']).' '.$equipe[0]['aprenom']); ?></b></a></center>
					<table class="table-small">
						<thead>
							<tr>
								<th style="width:60px"><small>Capitaine</small></th>
								<th>Sportif</th>
								<th style="width:200px">Licence</th>
							</tr>
						</thead>

						<tbody>

							<?php if (empty($equipe[0]['pid'])) { ?> 

							<tr class="vide">
								<td colspan="3">Aucun sportif</td>
							</tr>

							<?php } foreach ($equipe as $sportif) { ?>

							<tr>
								<td style="padding:0px">					
									<?php if ($equipe[0]['cid'] == $sportif['pid']) { ?>
									
									<input type="checkbox" checked />
									<label></label>

									<?php } ?>

								</td>
								<td>
									<div><?php echo stripslashes(strtoupper($sportif['pnom']).' '.$sportif['pprenom']); ?></div>
								</td>
								<td>
									<div><?php echo stripslashes($sportif['plicence']); ?></div>
								</td>
							</tr>

							<?php } ?>

						</tbody>
					</table>

				<?php } ?>

				</fieldset>

				<h2>Tarifs, Recharge &amp; Logement</h2>
				<fieldset>
					<div>
						<h3>Liste des participants</h3>

						<table>
							<thead>
								<tr>
									<th>Participant</th>
									<th>Tarif</th>
									<th>Recharge</th>
									<th>Montant</th>
									<th style="width:60px"><small>Logement</small></th>
									
									<?php if (!$ecole['ecole_lyonnaise']) { ?>

									<th>Logeur</th>

									<?php } ?>

								</tr>
							</thead>

							<tbody>

								<?php if (!count($participants)) { ?> 

								<tr class="vide">
									<td colspan="<?php echo $ecole['ecole_lyonnaise'] ? 5 : 6; ?>">Aucun participant</td>
								</tr>

								<?php } foreach ($participants as $participant) { ?>

								<tr>
									<td>
										<div><?php echo stripslashes(strtoupper($participant['nom']).' '.$participant['prenom']); ?></div>
									</td>

									<td>
										<div><?php echo stripslashes($participant['tarif']); ?></div>
									</td>

									<td>
										<div><?php echo stripslashes($participant['recharge']); ?></div>
									</td>

									<td>
										<center>
											<i><?php echo printMoney($participant['montant']); ?></i><?php
												if ($participant['retard']) echo ' <small style="font-variant:small-caps; font-weight:bold"> +Retard</small>'; ?></center>
									</td>

									<td style="padding:0px">
										<input type="checkbox" <?php if ($participant['logement']) echo 'checked '; ?>/>
										<label></label>
									</td>

									<?php if (!$ecole['ecole_lyonnaise']) { ?>

									<td>
										<div><?php echo stripslashes($participant['logeur']); ?></div>
									</td>

									<?php } ?>

								</tr>

								<?php } ?>

							</tbody>
						</table>
					</div>
				</fieldset>

				<h2>Paiements</h2>
				<fieldset>
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
					</div>
				</fieldset>
				</form>	

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
