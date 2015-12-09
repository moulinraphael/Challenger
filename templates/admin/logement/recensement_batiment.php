<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/logement/recensement_batiment.php *******/
/* Template des chambres du batiment ***********************/
/* *********************************************************/
/* Dernière modification : le 19/01/15 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Recensement du Bâtiment <?php echo $batiment; ?></h2>
								
				<?php

				$etages = in_array($batiment, array('A', 'B', 'C')) ? 4 : 6;
				
				?>

				<a href="<?php url('admin/module/logement/recensement'); ?>" class="excel_big">Retourner aux bâtiments</a>

				<table>
					<tbody>

						<?php 

						foreach (range(0, $etages) as $etage) {
							$chambres = $etage == 0 ? 
								($etages == 6 ? 8 : 17) : 
								($etages == 6 ? 16 : 17);

						?>

						<tr>
							<th style="width:100px;">Chambre</th>
							<th>Nom</th>
							<th>Prenom</th>
							<th>Surnom</th>
							<th>Telephone</th>
							<th>Email</th>
							<th style="width:150px">Etat</th>
						</tr>

							<?php
							
							foreach (range(1, $chambres) as $numero) {
								$chambre = sprintf('%s%d%02d', $batiment, $etage, $numero);
								$color = colorChambre($chambre);
								$proprio = isset($proprios[$chambre]) ? $proprios[$chambre] : null;
						
							?>

						<tr class="form">
							<td style="background-color:<?php echo $color; ?>;color:<?php echo colorContrast($color); ?>;text-align:center;font-weight:bold;">
								<input type="hidden" value="<?php echo $chambre; ?>" />
								<?php echo $chambre; ?>
							</td>
							<td><input type="text" value="<?php echo $proprio == null ? '' : stripslashes($proprio['nom_proprio']); ?>" /></td>
							<td><input type="text" value="<?php echo $proprio == null ? '' : stripslashes($proprio['prenom_proprio']); ?>" /></td>
							<td><input type="text" value="<?php echo $proprio == null ? '' : stripslashes($proprio['surnom_proprio']); ?>" /></td>
							<td><input type="text" value="<?php echo $proprio == null ? '' : stripslashes($proprio['telephone_proprio']); ?>" /></td>
							<td><input type="text" value="<?php echo $proprio == null ? '' : stripslashes($proprio['email_proprio']); ?>" /></td>
							<td style="background-color:<?php echo $proprio == null || empty($proprio['etat']) ? $colorsEtatChambre['pas_contacte'] : $colorsEtatChambre[$proprio['etat']]; ?>">
								<select style="background-color:transparent" onchange="$(this).css('background-color', $(this).children('option:selected').attr('color'));">
									
									<?php foreach ($labelsEtatChambre as $label => $description) { ?>

									<option value="<?php echo $label; ?>" color="<?php echo $colorsEtatChambre[$label]; ?>"<?php
										if (($proprio == null || empty($proprio['etat'])) && $label == 'pas_contacte' ||
										 	!empty($proprio['etat']) && $proprio['etat'] == $label) echo ' selected'; ?>><?php echo $description; ?></option>

									<?php } ?>

								</select>
							</td>
						</tr>

						<?php 

							}
						}

						?>

					</tbody>
				</table>

				<script type="text/javascript">
				$(function() {
					var block = false;
					$('td input[type=text], td input[type=number], td input[type=checkbox], td input[type=password], td select').change(function(event) {
						if (block == true)
							return;

						$parent = $(this).parent().parent();
						$first = $parent.children('td:first');
						$chambre = $first.children('input');
						$nom = $first.next().children('input');
						$prenom = $first.next().next().children('input');
		  				$surnom = $first.next().next().next().children('input');
		  				$telephone = $first.next().next().next().next().children('input');
		  				$email = $first.next().next().next().next().next().children('input');
		  				$etat = $first.next().next().next().next().next().next().children('select');

		  				$.ajax({
		  					url: "<?php url('admin/module/logement/'.$batiment.'?maj'); ?>",
		  					method: "POST",
						  	cache: false,
						  	dataType: 'json',
		  					data: {
		  						chambre: $chambre.val(),
		  						nom: $nom.val(),
		  						prenom: $prenom.val(),
		  						surnom: $surnom.val(),
		  						telephone: $telephone.val(),
		  						email: $email.val(),
		  						etat: $etat.val()
		  					},
		  					success: function(data) { 
								if (data.error == '1') {
									alert('Des personnes sont logées dans cette chambre, vous ne pouvez changer l\'état');
									block = true;
									$etat.val(data.etat).change();
									block = false;
								}
		  					}
		  				});
					});
	
				});
				</script>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
