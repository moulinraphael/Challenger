<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/competition/fanfarons_ecoles.php ********/
/* Template des fanfarons de la compétition ****************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Liste des Fanfarons (par Ecole)</h2>
				<a class="excel_big" href="?excel">Télécharger en XLSX groupé</a>


				<?php

				foreach ($fanfarons as $eid => $fanfarons_ecole) {

				?>
				
				<h3><?php echo stripslashes($fanfarons_ecole[0]['nom']); ?></h3>
				
				<a class="excel" href="?excel=<?php echo $eid; ?>">Télécharger en XLSX</a>
				<table class="table-small">
					<thead>
						<tr>
							<td colspan="4">
								<center>Quota Fanfaron : <b><?php echo $fanfarons_ecole[0]['quota_fanfaron']; ?></b>
								&nbsp; &nbsp; / &nbsp; &nbsp;
								Fanfarons :  <b><?php echo empty($fanfarons_ecole[0]['pid']) ? 0 : count($fanfarons_ecole); ?></b>
								</center>
							</td>
						</tr>

						<tr>
							<th>Nom</th>
							<th>Prenom</th>
							<th>Sexe</th>
							<th>Téléphone</th>
						</tr>
					</thead>

					<tbody>

						<?php if (empty($fanfarons_ecole[0]['pid'])) { ?> 

						<tr class="vide">
							<td colspan="4">Aucun fanfaron</td>
						</tr>

						<?php } else foreach ($fanfarons_ecole as $fanfaron) { ?>

						<tr>
							<td><?php echo stripslashes(strtoupper($fanfaron['pnom'])); ?></td>
							<td><?php echo stripslashes($fanfaron['pprenom']); ?></td>
							<td><?php echo printSexe($fanfaron['psexe'], false); ?></td>
							<td><?php echo stripslashes($fanfaron['ptelephone']); ?></td>
							
						</tr>

						<?php } ?>

					</tbody>
				</table>

				<?php } ?>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
