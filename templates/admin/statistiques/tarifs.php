<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/statistiques/tari.php *******************/
/* Template des stats sur les tarifs ***********************/
/* *********************************************************/
/* Dernière modification : le 23/01/15 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<h2>Statistiques sur les tarifs</h2>

				<?php
				
				$typesEcoles = [1 => 'Ecoles Lyonnaises', 0 => 'Ecoles Non Lyonnaises'];
				$typesSportifs = [1 => 'Tarifs Sportifs', 0 => 'Tarifs Non Sportifs'];

				foreach ($typesEcoles as $typeEcole => $labelEcole) {

					foreach ($typesSportifs as $typeSportif => $labelSportif) {

				?>

				<a name="tarifs_<?php echo $typeSportif.'_'.$typeEcole; ?>"></a>
				<h3><?php echo $labelEcole.' / '.$labelSportif; ?></h3>


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

							<td class="vertical"<?php echo !empty($ecole['tarifs'][$id]) ? ' style="background-color:#DFD"' : ''; ?>>
								<small style="line-height:0px; ">
									<b><?php echo $pnb = empty($ecole['tarifs'][$id]) ? 0 : $ecole['tarifs'][$id]; ?></b><br />
									<?php echo printMoney($tarif['tarif'] * $pnb); ?>
								</small>
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


				<?php } } ?>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
