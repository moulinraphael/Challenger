<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/ecoles/visibilite.php *******************/
/* Template de la gestion de la visibilite des tarifs ******/
/* *********************************************************/
/* Dernière modification : le 18/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<h2>Visibilité des tarifs</h2>

				<?php
				
				$typesEcoles = [1 => 'Ecoles Lyonnaises', 0 => 'Ecoles Non Lyonnaises'];
				$typesSportifs = [1 => 'Tarifs Sportifs', 0 => 'Tarifs Non Sportifs'];

				foreach ($typesEcoles as $typeEcole => $labelEcole) {

					foreach ($typesSportifs as $typeSportif => $labelSportif) {

				?>

				<a name="tarifs_<?php echo $typeSportif.'_'.$typeEcole; ?>"></a>
				<h3><?php echo $labelEcole.' / '.$labelSportif; ?></h3>

				<?php 

				if (!empty($maj) &&
					$typeEcole == (int) $ecole_lyonnaise &&
					$typeSportif == (int) $sportif) { 

				?>

				<div class="alerte alerte-success">
					<div class="alerte-contenu">
						La visibilité des tarifs de cette catégorie a bien été mise à jour
					</div>
				</div>

				<?php } ?>

				<form method="post" action="#tarifs_<?php echo $typeSportif.'_'.$typeEcole; ?>">
					<table>
						<thead>
							<tr>
								<th>Ecole</th>

								<?php
								
								$nbTarifs = 0;

								foreach ($tarifs as $id => $tarif) {
									
									if (!preg_match('`^'.(!$typeSportif ? 'non' : '').'sportif`', $tarif['type']) ||
										(int) $tarif['ecole_lyonnaise'] != $typeEcole)
										continue;

									$nbTarifs++;

								 ?>

								<th class="vertical"><span><?php echo stripslashes($tarif['nom']); ?></span></th>

								<?php } ?>

							</tr>
						</thead>

						<tbody>

							<?php

							$nbEcoles = 0;
							foreach ($ecoles as $ecole) {

								if ((int) $ecole['ecole_lyonnaise'] != $typeEcole)
									continue;

							$nbEcoles++;

							?>

							<tr class="form">
								<td><center><?php echo stripslashes($ecole['nom']); ?></center></td>
								
								<?php

								foreach ($tarifs as $id => $tarif) {

									if (!preg_match('`^'.(!$typeSportif ? 'non' : '').'sportif`', $tarif['type']) ||
										(int) $tarif['ecole_lyonnaise'] != $typeEcole)
										continue;

								?>

								<td class="vertical">
									<input type="checkbox" id="tarifs_<?php echo $typeSportif; ?>_<?php echo $ecole['id'].'_'.$id; ?>" name="tarifs_<?php echo $ecole['id']; ?>[]" value="<?php echo $id; ?>"<?php 
										if (in_array($id, array_keys($ecole['tarifs']))) echo ' checked'; 
										if (!empty($ecole['tarifs'][$id])) echo ' disabled'; ?> />
									<label for="tarifs_<?php echo $typeSportif; ?>_<?php echo $ecole['id'].'_'.$id; ?>"></label>
								</td>

								<?php } ?>

							</tr>

							<?php } if (!$nbEcoles) { ?>

							<tr class="vide">
								<td colspan="<?php echo 1 + $nbTarifs; ?>">Aucune école</td>
							</tr>

							<?php } ?>

						</tbody>
					</table>

					<center>
						<input type="hidden" name="ecole_lyonnaise" value="<?php echo $typeEcole; ?>" />
						<input type="hidden" name="sportif" value="<?php echo $typeSportif; ?>" />
						<input type="submit" name="maj" class="success" value="Mise à jour de la visibilité" style="margin-top:-40px; margin-bottom:40px" />
					</center>
				</form>


			<?php } } ?>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
