<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/competition/pompoms.php *****************/
/* Template des pompoms de la compétition ******************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Liste des Pompoms</h2>
				
				<a class="excel" href="?excel">Télécharger en XLSX</a>
				<table class="table-small">
					<thead>
						<tr>
							<td colspan="5">
								<center>Pompoms :  <b><?php echo count($pompoms); ?></b>
								</center>
							</td>
						</tr>

						<tr>
							<th>Nom</th>
							<th>Prenom</th>
							<th>Sexe</th>
							<th>Ecole</th>
							<th>Téléphone</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($pompoms)) { ?> 

						<tr class="vide">
							<td colspan="5">Aucun pompom</td>
						</tr>

						<?php } else foreach ($pompoms as $pompom) { ?>

						<tr>
							<td><?php echo stripslashes(strtoupper($pompom['pnom'])); ?></td>
							<td><?php echo stripslashes($pompom['pprenom']); ?></td>
							<td><?php echo printSexe($pompom['psexe'], false); ?></td>
							<td><?php echo stripslashes($pompom['enom']); ?></td>
							<td><?php echo stripslashes($pompom['ptelephone']); ?></td>
							
						</tr>

						<?php } ?>

					</tbody>
				</table>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
