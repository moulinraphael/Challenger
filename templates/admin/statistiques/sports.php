<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/statistiques/sports.php *****************/
/* Template des stats sur les sports ***********************/
/* *********************************************************/
/* Dernière modification : le 23/01/15 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<h2>Statistiques sur les sports</h2>

				<table>
					<thead>
						<tr>
							<th>Ecole</th>

							<?php
							
							$nbSports = 0;

							foreach ($sports as $id => $sport) {
								

								$nbSports++;

							 ?>

							<th class="vertical"><span><?php echo stripslashes($sport['sport']).' '.printSexe($sport['sexe']); ?></span></th>

							<?php } ?>

						</tr>
					</thead>

					<tbody>

						<?php

						$nbEcoles = 0;
						foreach ($ecoles as $ecole) {

						$nbEcoles++;

						?>

						<tr class="form">
							<td><center><?php echo stripslashes($ecole['nom']); ?></center></td>
							
							<?php

							foreach ($sports as $id => $sport) {

							?>

							<td class="vertical"<?php echo !empty($ecole['sports'][$id]) ? ' style="background-color:#DFD"' : ''; ?>>
								<center><b><?php echo empty($ecole['sports'][$id]) ? 0 : $ecole['sports'][$id]; ?></b></center>
							</td>

							<?php } ?>

						</tr>

						<?php } if (!$nbEcoles) { ?>

						<tr class="vide">
							<td colspan="<?php echo 1 + $nbSports; ?>">Aucune école</td>
						</tr>

						<?php } ?>

					</tbody>
				</table>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
