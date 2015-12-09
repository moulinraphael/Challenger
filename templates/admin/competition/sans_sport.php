<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/competition/sans_sport.php **************/
/* Template des sportifs sans sport ************************/
/* *********************************************************/
/* Dernière modification : le 20/01/15 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Liste des Sportifs sans Sport</h2>
				
				<a class="excel" href="?excel">Télécharger en XLSX</a>
				<table class="table-small">
					<thead>
						<tr>
							<td colspan="6">
								<center>Concernés :  <b><?php echo count($sans_sport); ?></b>
								</center>
							</td>
						</tr>

						<tr>
							<th>Nom</th>
							<th>Prenom</th>
							<th>Sexe</th>
							<th>Ecole</th>
							<th>Licence</th>
							<th>Téléphone</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($sans_sport)) { ?> 

						<tr class="vide">
							<td colspan="6">Aucun concerné</td>
						</tr>

						<?php } else foreach ($sans_sport as $participant) { ?>

						<tr>
							<td><?php echo stripslashes(strtoupper($participant['nom'])); ?></td>
							<td><?php echo stripslashes($participant['prenom']); ?></td>
							<td><?php echo printSexe($participant['sexe'], false); ?></td>
							<td><?php echo stripslashes($participant['enom']); ?></td>
							<td><?php echo stripslashes($participant['licence']); ?></td>
							<td><?php echo stripslashes($participant['telephone']); ?></td>
							
						</tr>

						<?php } ?>

					</tbody>
				</table>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
