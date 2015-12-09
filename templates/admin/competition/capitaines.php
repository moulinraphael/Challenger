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
				
				<h2>Liste des Capitaines</h2>
				
				<a class="excel" href="?excel">Télécharger en XLSX</a>
				<table>
					<thead>
						<tr>
							<td colspan="8">
								<center>Capitaines :  <b><?php echo count($capitaines); ?></b>
								</center>
							</td>
						</tr>

						<tr>
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

						<?php if (!count($capitaines)) { ?> 

						<tr class="vide">
							<td colspan="7">Aucun capitaine</td>
						</tr>

						<?php } else foreach ($capitaines as $capitaine) { ?>

						<tr>
							<td><?php echo stripslashes(strtoupper($capitaine['pnom'])); ?></td>
							<td><?php echo stripslashes($capitaine['pprenom']); ?></td>
							<td><?php echo printSexe($capitaine['psexe'], false); ?></td>
							<td><?php echo stripslashes($capitaine['enom']); ?></td>
							<td><?php echo stripslashes($capitaine['sport']).' '.printSexe($capitaine['ssexe']); ?></td>
							<td><?php echo stripslashes($capitaine['plicence']); ?></td>
							<td><?php echo stripslashes($capitaine['ptelephone']); ?></td>
							
						</tr>

						<?php } ?>

					</tbody>
				</table>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
