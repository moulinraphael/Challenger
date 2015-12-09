<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/classement/tableau.php ******************/
/* Template du tableau des classements *********************/
/* *********************************************************/
/* Dernière modification : le 17/02/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<h2>Tableau des Points</h2>


				<table class="table-small">
					<thead>
						<tr>
							<th>Ecole</th>
							<th>Points</th>
							<th>Classement</th>
						</tr>
					</thead>

					<tbody>

						<?php

						$i = 0;
						foreach ($ecoles as $ecole) {
							if (!$ecole['points'])
								continue;

						?>

						<tr>
							<td><?php if ($i < 3) echo '<img src="'.
								url('assets/images/actions/'.($i == 0 ? 'Gold' : 
										($i == 1 ? 'Argent' : 'Bronze')).'.png', false, false).
									'" alt="" /> ';
									echo stripslashes($ecole['nom']); ?></td>
							<td><?php echo $ecole['points']; ?></td>
							<td><?php echo ++$i; ?></td>
						</tr>

						<?php }

						if (!$i) { ?> 

						<tr class="vide">
							<td colspan="3">Aucune école</td>
						</tr>

						<?php }  ?>

					</tbody>
				</table>



				<h2>Tableau des Classements</h2>


				<table>
					<thead>
						<tr>
							<th>Sport</th>
							<th>Coefficient</th>
							<th>1er</th>
							<th>Points</th>
							<th>2e</th>
							<th>Points</th>
							<th>3e</th>
							<th>Points</th>
							<th>3e-Ex</th>
							<th>Points</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($classements)) { ?> 

						<tr class="vide">
							<td colspan="10">Aucun classement</td>
						</tr>

						<?php } foreach ($classements as $classement) { ?>

						<tr>
							<td><?php echo stripslashes($classement['sport']).' '.printSexe($classement['sexe']); ?></td>
							<td><?php echo $classement['coeff']; ?></td>
							<td>
								<img src="<?php url('assets/images/actions/Gold.png'); ?>" alt="" />
								<?php echo stripslashes($ecoles[$classement['id_ecole_1']]['nom']); ?>
							</td>
							<td><?php echo APP_POINTS_1ER * $classement['coeff']; ?></td>
							<td>
								<img src="<?php url('assets/images/actions/'.($classement['ex_12'] ? 'Gold' : 'Argent').'.png'); ?>" alt="" />
								<?php echo stripslashes($ecoles[$classement['id_ecole_2']]['nom']); ?>
							</td>
							<td><?php echo ($classement['ex_12'] ? APP_POINTS_1ER : APP_POINTS_2E) * $classement['coeff']; ?></td>
							<td>
								<img src="<?php url('assets/images/actions/'.
									($classement['ex_12'] ? ($classement['ex_23'] ? 'Gold' : 'Bronze') :  ($classement['ex_23'] ? 'Argent' : 'Bronze')).
									'.png'); ?>" alt="" />
								<?php echo stripslashes($ecoles[$classement['id_ecole_3']]['nom']); ?>
							</td>
							<td><?php echo ($classement['ex_12'] ? ($classement['ex_23'] ? APP_POINTS_1ER : APP_POINTS_3E) :  ($classement['ex_23'] ? APP_POINTS_2E : APP_POINTS_3E)) * 
								$classement['coeff']; ?></td>
							<td>

								<?php if ($classement['ex_3'])  { ?> 

								<img src="<?php url('assets/images/actions/'.($classement['ex_12'] && $classement['ex_23'] ? 'Gold' : ($classement['ex_23'] ? 'Argent' : 'Bronze')).'.png'); ?>" alt="" />
								<?php echo stripslashes($ecoles[$classement['id_ecole_3ex']]['nom']); 

								} ?>

							</td>
							<td><?php echo $classement['ex_3'] ? ($classement['ex_12'] && $classement['ex_23'] ? APP_POINTS_1ER : ($classement['ex_23'] ? APP_POINTS_2E : APP_POINTS_3E)) * $classement['coeff'] : ''; ?></td>
						</tr>

						<?php } ?>

					</tbody>
				</table>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
