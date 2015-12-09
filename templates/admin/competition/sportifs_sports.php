<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/competition/sportifs_sports.php *********/
/* Template des sportifs de la compétition *****************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Liste des Sportifs (par Sports)</h2>
				<a class="excel_big" href="?excel">Télécharger en XLSX groupé</a>


				<?php

				foreach ($sportifs as $sid => $sportifs_sport) {

				?>
				
				<h3><?php echo stripslashes($sportifs_sport[0]['sport']).' '.printSexe($sportifs_sport[0]['sexe']); ?></h3>
				
				<a class="excel" href="?excel=<?php echo $sid; ?>">Télécharger en XLSX</a>
				<table class="table-small">
					<thead>
						<tr>
							<td colspan="7">
								<center>Quota Max : <b><?php echo $sportifs_sport[0]['quota_max']; ?></b>

								<?php if (!empty($sportifs_sport[0]['quota_inscription'])) { ?>
								
								&nbsp; &nbsp; / &nbsp; &nbsp;
								Quota Inscription :  <b><?php echo $sportifs_sport[0]['quota_inscription']; ?></b>

								<?php } ?>

								&nbsp; &nbsp; / &nbsp; &nbsp;
								Inscrits :  <b><?php echo empty($sportifs_sport[0]['pid']) ? 0 : count($sportifs_sport); ?></b>
								</center>
							</td>
						</tr>

						<tr>
							<th style="width:60px">Capitaine</th>
							<th>Nom</th>
							<th>Prenom</th>
							<th>Sexe</th>
							<th>Ecole</th>
							<th>Licence</th>
							<th>Téléphone</th>
						</tr>
					</thead>

					<tbody>

						<?php if (empty($sportifs_sport[0]['pid'])) { ?> 

						<tr class="vide">
							<td colspan="7">Aucun sportif</td>
						</tr>

						<?php } else foreach ($sportifs_sport as $sportif) { ?>

						<tr>
							<td style="padding:0px">					
								<?php if ($sportif['id_capitaine'] == $sportif['pid']) { ?>
								
								<input type="checkbox" checked />
								<label></label>

								<?php } ?>

							</td>
							<td><?php echo stripslashes(strtoupper($sportif['pnom'])); ?></td>
							<td><?php echo stripslashes($sportif['pprenom']); ?></td>
							<td><?php echo printSexe($sportif['psexe'], false); ?></td>
							<td><?php echo stripslashes($sportif['enom']); ?></td>
							<td><?php echo stripslashes($sportif['plicence']); ?></td>
							<td><?php echo stripslashes($sportif['ptelephone']); ?></td>
							
						</tr>

						<?php } ?>

					</tbody>
				</table>

				<?php } ?>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
