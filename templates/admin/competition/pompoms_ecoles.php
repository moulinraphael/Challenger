<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/competition/pompoms_ecoles.php **********/
/* Template des pompoms de la compétition *****************/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Liste des Pompoms (par Ecole)</h2>
				<a class="excel_big" href="?excel">Télécharger en XLSX groupé</a>


				<?php

				foreach ($pompoms as $eid => $pompoms_ecole) {

				?>
				
				<h3><?php echo stripslashes($pompoms_ecole[0]['nom']); ?></h3>
				
				<a class="excel" href="?excel=<?php echo $eid; ?>">Télécharger en XLSX</a>
				<table class="table-small">
					<thead>
						<tr>
							<td colspan="4">
								<center>Quota Pompom : <b><?php echo $pompoms_ecole[0]['quota_pompom']; ?></b>
								&nbsp; &nbsp; / &nbsp; &nbsp;
								Pompoms :  <b><?php echo empty($pompoms_ecole[0]['pid']) ? 0 : count($pompoms_ecole); ?></b>
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

						<?php if (empty($pompoms_ecole[0]['pid'])) { ?> 

						<tr class="vide">
							<td colspan="4">Aucun pompom</td>
						</tr>

						<?php } else foreach ($pompoms_ecole as $pompom) { ?>

						<tr>
							<td><?php echo stripslashes(strtoupper($pompom['pnom'])); ?></td>
							<td><?php echo stripslashes($pompom['pprenom']); ?></td>
							<td><?php echo printSexe($pompom['psexe'], false); ?></td>
							<td><?php echo stripslashes($pompom['ptelephone']); ?></td>
							
						</tr>

						<?php } ?>

					</tbody>
				</table>

				<?php } ?>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
