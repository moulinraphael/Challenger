<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/logement/chambres_batiment.php **********/
/* Template des chambres du batiment ***********************/
/* *********************************************************/
/* Dernière modification : le 19/01/15 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Chambres du Bâtiment <?php echo $batiment; ?></h2>
				<?php

				$etages = in_array($batiment, array('A', 'B', 'C')) ? 4 : 6;
				$nbfilles = in_array($batiment, array('A', 'B', 'C')) ? 5 : 3;

				?>

				<a href="<?php url('admin/module/logement/chambres'); ?>" class="excel_big">Retourner aux bâtiments</a>
				<table>
					<tbody>

						<?php 

						foreach (range(0, $etages) as $etage) {
							$chambres = $etage == 0 ? 
								($etages == 6 ? 8 : 17) : 
								($etages == 6 ? 16 : 17);


						ob_start();

						?>

						<tr>
							<th style="width:100px;"><?php echo $batiment.$etage; ?></th>
							<th style="width:150px">Etat</th>
							<?php for($i = 1; $i <= $nbfilles; $i++) { ?>
							<th>Fille <?php echo $i; ?></th>
							<?php } ?>
							<th style="width:150px">Clefs</th>
							<th style="width:100px">Lit de Camp</th>
						</tr>

						<?php

						$header = ob_get_clean();
						
						$nb = 0;
						foreach (range(1, $chambres) as $numero) {
							$chambre = sprintf('%s%d%02d', $batiment, $etage, $numero);
							$color = colorChambre($chambre);
							$proprio = isset($proprios[$chambre]) ? $proprios[$chambre] : null;
						
						if ($proprio == null)
							continue;

						if (!$nb)
							echo $header;

						$nb++;

						?>

						<tr class="form">
							<td style="background-color:<?php echo $color; ?>;color:<?php echo colorContrast($color); ?>;text-align:center;font-weight:bold;">
								<input type="hidden" value="<?php echo $chambre; ?>" />
								<?php echo $chambre; ?>
							</td>
							<td style="background-color:<?php echo empty($proprio['etat']) ? $colorsEtatChambre['pas_contacte'] : $colorsEtatChambre[$proprio['etat']]; ?>">
								<div style="background-color:<?php echo empty($proprio['etat']) ? $colorsEtatChambre['pas_contacte'] : $colorsEtatChambre[$proprio['etat']]; ?>"><?php echo $labelsEtatChambre[!empty($proprio['etat']) ? $proprio['etat'] : 'pas_contacte']; ?></div>
							</td>

							<?php

							$filles_proprio = explode(',', $proprio['filles']);
							foreach ($filles_proprio as $key => $fille) {
								if (empty($filles[$fille]))
									unset($filles_proprio[$key]);
							}
							$filles_proprio = array_values($filles_proprio);

							?>

							<?php for($i = 0; $i < $nbfilles; $i++) { ?>

							<td><center style="height:auto; padding:0px 5px; line-height:20px;">
								
								<?php 

								if (isset($filles_proprio[$i])) { 
									$fille = $filles[$filles_proprio[$i]];

								?>
								
								<b><?php echo stripslashes(strtoupper($fille['nom']).' '.$fille['prenom']); ?></b><br />
								<small><?php echo stripslashes($fille['enom']).' / '; 
									echo empty($fille['sid']) ? '<i>Sans sport</i>' :
										($fille['sport'].' '.printSexe($fille['sexe'])); ?><br />
									<?php echo stripslashes($fille['telephone']); ?></small>

								<?php } ?>

								</center></td>
							
							<?php } ?>

							<td style="background-color:<?php echo empty($proprio['etat_clef']) ? $colorsEtatClef['pas_recue'] : $colorsEtatClef[$proprio['etat_clef']]; ?>">
								<select style="background-color:transparent;" onchange="$(this).parent().css('background-color', $(this).children('option:selected').attr('color'));">
									
									<?php foreach ($labelsEtatClef as $label => $description) { ?>

									<option value="<?php echo $label; ?>" color="<?php echo $colorsEtatClef[$label]; ?>"<?php
										if (empty($proprio['etat_clef']) && $label == 'pas_recue' ||
										 	!empty($proprio['etat_clef']) && $proprio['etat_clef'] == $label) echo ' selected'; ?>><?php echo $description; ?></option>

									<?php } ?>

								</select>
							</td>

							<td style="background-color:<?php echo !empty($proprio['lit_camp']) ? '#4B7' : '#B44'; ?>">
								<input id="lit_camp_<?php echo $proprio['id']; ?>" onchange="$(this).parent().css('background-color', $(this).is(':checked') ? '#4B7' : '#B44');" type="checkbox" <?php if (!empty($proprio['lit_camp'])) echo 'checked '; ?>/>
								<label for="lit_camp_<?php echo $proprio['id']; ?>" style="line-height:100% !important; height:100% !important;"></label>
							</td>

						</tr>

						<?php 

						}						

						}

						?>

					</tbody>
				</table>

				

				<center>
					<img style="height:80px;" src="<?php url('assets/images/form/etatsClefs.png'); ?>" alt="" />
				</center>

				<script type="text/javascript">
				$(function() {
					var block = false;
					$('td input[type=text], td input[type=number], td input[type=checkbox], td input[type=password], td select').change(function(event) {
						if (block == true)
							return;

						$parent = $(this).parent().parent();
						$first = $parent.children('td:first');
						$chambre = $first.children('input');
						$clef = $first<?php echo str_repeat('.next()', 2+$nbfilles); ?>.children('select');
						$lit = $first<?php echo str_repeat('.next()', 2+$nbfilles); ?>.next().children('input');

		  				$.ajax({
		  					url: "<?php url('admin/module/logement/_'.$batiment.'?maj'); ?>",
		  					method: "POST",
						  	cache: false,
		  					data: {
		  						chambre: $chambre.val(),
		  						clef: $clef.val(),
		  						lit: $lit.is(':checked') ? '1' : '0'
		  					}
		  				});
					});
	
				});
				</script>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
