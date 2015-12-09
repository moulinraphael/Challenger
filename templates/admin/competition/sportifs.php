<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/competition/sportifs.php ****************/
/* Template des sportifs de la compétition *****************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Liste des Sportifs </h2>
				
				<a class="excel" href="?excel">Télécharger en XLSX</a>
				<table>
					<thead>
						<tr>
							<td colspan="8">
								<center>Inscrits :  <b><?php echo count($sportifs); ?></b>
								</center>
							</td>
						</tr>

						<tr>
							<th style="width:60px">Capitaine</th>
							<th>Nom</th>
							<th>Prenom</th>
							<th>Sexe</th>
							<th>Ecole</th>
							<th>Sport</th>
							<th>Licence</th>
							<th>Téléphone</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($sportifs)) { ?> 

						<tr class="vide">
							<td colspan="8">Aucun sportif</td>
						</tr>

						<?php } else foreach ($sportifs as $sportif) { ?>

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
							<td><?php echo stripslashes($sportif['sport']).' '.printSexe($sportif['ssexe']); ?></td>
							<td><?php echo stripslashes($sportif['plicence']); ?></td>
							<td><?php echo stripslashes($sportif['ptelephone']); ?></td>
							
						</tr>

						<?php } ?>

					</tbody>
				</table>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
