<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/competition/sportifs_ecoles.php *********/
/* Template des sportifs de la compétition *****************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Liste des Sportifs (par Ecole)</h2>
				<a class="excel_big" href="?excel">Télécharger en XLSX groupé</a>


				<?php

				foreach ($sportifs as $eid => $sportifs_ecole) {

				?>
				
				<h3><?php echo stripslashes($sportifs_ecole[0]['nom']); ?></h3>
				
				<a class="excel" href="?excel=<?php echo $eid; ?>">Télécharger en XLSX</a>
				<table class="table-small">
					<thead>
						<tr>
							<td colspan="7">
								<center>Quota Sportif : <b><?php echo $sportifs_ecole[0]['quota_sportif']; ?></b>
								&nbsp; &nbsp; / &nbsp; &nbsp;
								Sportifs :  <b><?php echo empty($sportifs_ecole[0]['pid']) ? 0 : count($sportifs_ecole); ?></b>
								</center>
							</td>
						</tr>

						<tr>
							<th style="width:60px">Capitaine</th>
							<th>Nom</th>
							<th>Prenom</th>
							<th>Sexe</th>
							<th>Sport</th>
							<th>Licence</th>
							<th>Téléphone</th>
						</tr>
					</thead>

					<tbody>

						<?php if (empty($sportifs_ecole[0]['pid'])) { ?> 

						<tr class="vide">
							<td colspan="7">Aucun sportif</td>
						</tr>

						<?php } else foreach ($sportifs_ecole as $sportif) { ?>

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
							<td><?php echo stripslashes($sportif['sport']).' '.printSexe($sportif['ssexe']); ?></td>
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
