<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/competition/fanfarons.php ***************/
/* Template des fanfarons de la compétition ****************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Liste des Fanfarons</h2>
				
				<a class="excel" href="?excel">Télécharger en XLSX</a>
				<table class="table-small">
					<thead>
						<tr>
							<td colspan="5">
								<center>Fanfarons :  <b><?php echo count($fanfarons); ?></b>
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

						<?php if (!count($fanfarons)) { ?> 

						<tr class="vide">
							<td colspan="5">Aucun fanfaron</td>
						</tr>

						<?php } else foreach ($fanfarons as $fanfaron) { ?>

						<tr>
							<td><?php echo stripslashes(strtoupper($fanfaron['pnom'])); ?></td>
							<td><?php echo stripslashes($fanfaron['pprenom']); ?></td>
							<td><?php echo printSexe($fanfaron['psexe'], false); ?></td>
							<td><?php echo stripslashes($fanfaron['enom']); ?></td>
							<td><?php echo stripslashes($fanfaron['ptelephone']); ?></td>
							
						</tr>

						<?php } ?>

					</tbody>
				</table>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
