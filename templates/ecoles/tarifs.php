<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/ecoles/tarifs.php *****************************/
/* Template du détail des tarifs ***************************/
/* *********************************************************/
/* Dernière modification : le 09/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/ecoles/_header_ecoles.php';

?>
			
				<h2>Détails des tarifs</h2>


				<div class="alerte">
					<div class="alerte-contenu">
						<ul style="margin-left:20px; ">

							<?php foreach ($tarifs_groupes as $groupe => $tarifs) { ?>
							
							<li style="margin-bottom:20px; list-style-type:none;">
								<h3 style="text-align:left"><?php echo empty($typesTarifs[$groupe]) ? $groupe : $typesTarifs[$groupe]; ?></h4>
								<ul style="margin-left:20px; list-style-type:none">

									<?php foreach ($tarifs as $tarif) { ?>
									
									<li style="margin-bottom:15px">
										<b><?php echo $tarif['nom']; ?></b> (<?php echo sprintf('%.2f €', $tarif['tarif']); ?>) 
										<?php echo printLogementTarif($tarif['logement']); ?><br />
										<?php echo nl2br($tarif['description']); ?><br />
										<?php if (!empty($tarif['id_sport_special'])) { ?>
										<i>Sport Spécial : </i> <b><?php echo stripslashes($tarif['sport']); ?></b> <?php echo printSexe($tarif['sexe']); ?><br />
										<?php } ?>
										<div class="extras">
											
											<?php if (in_array($tarif['for_pompom'], ['yes', 'no'])) { ?>
											
											<input type="checkbox" readonly <?php if ($tarif['for_pompom'] == 'yes') echo 'checked '; ?>/>
											<label class="extra-pompom"></label>
											
											<?php } if (in_array($tarif['for_cameraman'], ['yes', 'no'])) { ?>
											
											<input type="checkbox" readonly <?php if ($tarif['for_cameraman'] == 'yes') echo 'checked '; ?>/>
											<label class="extra-video"></label>
											
											<?php } if (in_array($tarif['for_fanfaron'], ['yes', 'no'])) { ?>
											
											<input type="checkbox" readonly <?php if ($tarif['for_fanfaron'] == 'yes') echo 'checked '; ?>/>
											<label class="extra-fanfaron"></label>
											
											<?php } ?>

										</div>
										<br />
									</li>

									<?php } ?>

								</ul>
							</li>

							<?php } ?>

						</ul>
					</div>
				</div>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
