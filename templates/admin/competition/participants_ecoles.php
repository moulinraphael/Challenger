<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/competition/participants_ecole.php ******/
/* Template des participants de la compétition *************/
/* *********************************************************/
/* Dernière modification : le 23/02/15 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Liste des Participants (par Ecole)</h2>
				<a class="excel_big" href="?excel">Télécharger en XLSX groupé</a>


				<?php

				foreach ($participants as $eid => $participants_ecole) {

				?>
				
				<h3><?php echo stripslashes($participants_ecole[0]['nom']); ?></h3>
				
				<a class="excel" href="?excel=<?php echo $eid; ?>">Télécharger en XLSX</a>
				<table>
					<thead>
						<tr>
							<td colspan="10">
								<center>Inscrits :  <b><?php echo empty($participants_ecole[0]['pid']) ? 0 : count($participants_ecole); ?></b>
								</center>
							</td>
						</tr>

						<tr>
							<th>Nom</th>
							<th>Prenom</th>
							<th>Sexe</th>
							<th style="width:60px">Sportif</th>
							<th style="width:60px">Fanfaron</th>
							<th style="width:60px">Pompom</th>
							<th>Téléphone</th>
							<th>Recharge</th>
							<th>Tarif</th>
							<th style="width:60px">Logement</th>
						</tr>
					</thead>

					<tbody>

						<?php if (empty($participants_ecole[0]['pid'])) { ?> 

						<tr class="vide">
							<td colspan="10">Aucun participant</td>
						</tr>

						<?php } else foreach ($participants_ecole as $participant) { ?>

						<tr>
							<td><?php echo stripslashes(strtoupper($participant['pnom'])); ?></td>
							<td><?php echo stripslashes($participant['pprenom']); ?></td>
							<td><?php echo printSexe($participant['psexe'], false); ?></td>
							<td style="padding:0px">					
								<?php if ($participant['sportif']) { ?>
								
								<input type="checkbox" checked />
								<label></label>

								<?php } ?>
							</td>
							<td style="padding:0px">					
								<?php if ($participant['fanfaron']) { ?>
								
								<input type="checkbox" checked />
								<label></label>

								<?php } ?>
							</td>
							<td style="padding:0px">					
								<?php if ($participant['pompom']) { ?>
								
								<input type="checkbox" checked />
								<label></label>

								<?php } ?>
							</td>
							<td><?php echo stripslashes($participant['ptelephone']); ?></td>
							<td><?php echo stripslashes($participant['rnom']); ?></td>
							<td><?php echo stripslashes($participant['tnom']); ?></td>
							<td style="padding:0px">					
								<?php if ($participant['logement']) { ?>
								
								<input type="checkbox" checked />
								<label></label>

								<?php } ?>
							</td>
						</tr>

						<?php } ?>

					</tbody>
				</table>

				<?php } ?>
				


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
