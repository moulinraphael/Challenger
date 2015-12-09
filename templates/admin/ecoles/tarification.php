<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/ecoles/tarification.php *****************/
/* Template de la gestion des tarifs ***********************/
/* *********************************************************/
/* Dernière modification : le 21/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<h2>Liste des Tarifications</h2>

				<?php
				if (isset($add) ||
					isset($modify) ||
					!empty($delete)) {
				?>

				<div class="alerte alerte-<?php echo !empty($add) || !empty($modify) || !empty($delete) ? 'success' : 'erreur'; ?>">
					<div class="alerte-contenu">
						<?php
						if (!empty($modify)) echo 'Le tarif a bien été édité';
						else if (!empty($delete)) echo 'Le tarif a bien été supprimé';
						else if (!empty($add)) echo 'Le tarif a bien été ajouté';
						?>
					</div>
				</div>

				<?php } ?>

				<form method="post">
				<h3>Tarifs des Ecoles Lyonnaises</h3>
				<table>
					<thead>
						<tr class="form">
							<td>
								<select name="type[]">
									<?php foreach ($typesTarifs as $label => $nom) { ?>

									<option value="<?php echo $label; ?>"><?php echo $nom; ?></option>

									<?php } ?>

								</select>
							</td>
							<td>
								<select name="for_pompom[]">
									<option value="yes">Oui</option>
									<option value="or" selected></option>
									<option value="no">Non</option>
								</select>
							</td>
							<td>
								<select name="for_cameraman[]">
									<option value="yes">Oui</option>
									<option value="or" selected></option>
									<option value="no">Non</option>
								</select>
							</td>
							<td>
								<select name="for_fanfaron[]">
									<option value="yes">Oui</option>
									<option value="or" selected></option>
									<option value="no">Non</option>
								</select>
							</td>
							<td><input type="text" name="ordre[]" placeholder="Ordre..." /></td>
							<td><input type="text" name="nom[]" placeholder="Nom..." /></td>
							<td><textarea name="description[]" placeholder="Description..."></textarea></td>
							<td>
								<input type="checkbox" name="form-logement" id="form-logement" value="0" />
								<label for="form-logement"></label>
							</td>
							<td><input type="number" step="any" min="0" name="montant[]" placeholder="Montant..." /></td>
							<td>
								<select name="special[]">
									<option value="" selected></option>

									<?php foreach ($sports as $sid => $sport) { ?>

									<option value="<?php echo $sid; ?>"><?php echo stripslashes($sport['sport']).' '.strip_tags(printSexe($sport['sexe'])); ?></option>

									<?php } ?>

								</select>
							</td>
							<td class="actions">
								<button type="submit" name="add">
									<img src="<?php url('assets/images/actions/add.png'); ?>" alt="Add" />
								</button>

								<input type="hidden" name="ecole_lyonnaise[]" value="1" /> 
								<input type="hidden" name="id[]" />
							</td>
						</tr>


						<tr>
							<th style="width:200px">Participants</th>
							<th style="width:70px"><small>Pompom</small></th>
							<th style="width:70px"><small>Caméraman</small></th>
							<th style="width:70px"><small>Fanfaron</small></th>
							<th>Ordre</th>
							<th>Nom</th>
							<th>Description</th>
							<th>Logement</th>
							<th>Montant</th>
							<th><small>Sport Spécial</small></th>
							<th class="actions">Actions</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($tarifs_lyonnais)) { ?> 

						<tr class="vide">
							<td colspan="11">Aucun tarif</td>
						</tr>

						<?php } foreach ($tarifs_lyonnais as $tarif) { ?>

						<tr class="form">
							<td>
								<select name="type[]">
									
									<?php foreach ($typesTarifs as $label => $nom) { ?>

									<option value="<?php echo $label; ?>"<?php if ($tarif['type'] == $label) echo ' selected'; ?>><?php echo $nom; ?></option>

									<?php } ?>

								</select>
							</td>

							<td>
								<select name="for_pompom[]">
									<option value="yes"<?php if ($tarif['for_pompom'] == 'yes') echo ' selected'; ?>>Oui</option>
									<option value="or"<?php if (!in_array($tarif['for_pompom'], ['yes', 'no'])) echo ' selected'; ?>></option>
									<option value="no"<?php if ($tarif['for_pompom'] == 'no') echo ' selected'; ?>>Non</option>
								</select>
							</td>
							<td>
								<select name="for_cameraman[]">
									<option value="yes"<?php if ($tarif['for_cameraman'] == 'yes') echo ' selected'; ?>>Oui</option>
									<option value="or"<?php if (!in_array($tarif['for_cameraman'], ['yes', 'no'])) echo ' selected'; ?>></option>
									<option value="no"<?php if ($tarif['for_cameraman'] == 'no') echo ' selected'; ?>>Non</option>
								</select>
							</td>
							<td>
								<select name="for_fanfaron[]">
									<option value="yes"<?php if ($tarif['for_fanfaron'] == 'yes') echo ' selected'; ?>>Oui</option>
									<option value="or"<?php if (!in_array($tarif['for_fanfaron'], ['yes', 'no'])) echo ' selected'; ?>></option>
									<option value="no"<?php if ($tarif['for_fanfaron'] == 'no') echo ' selected'; ?>>Non</option>
								</select>
							</td>

							<td><input type="text" name="ordre[]" value="<?php echo stripslashes((int) $tarif['ordre']); ?>" /></td>
							<td><input type="text" name="nom[]" value="<?php echo stripslashes($tarif['nom']); ?>" /></td>
							<td><textarea name="description[]"><?php echo stripslashes($tarif['description']); ?></textarea></td>
							<td>
								<input type="checkbox" name="logement[]" id="form-logement-<?php echo $tarif['id']; ?>" value="<?php echo $tarif['id']; ?>" <?php if (!empty($tarif['logement'])) echo 'checked '; ?>/>
								<label for="form-logement-<?php echo $tarif['id']; ?>"></label>
							</td>
							<td><input type="number" step="any" min="0" name="montant[]" value="<?php echo sprintf('%.2f', (float) $tarif['tarif']); ?>" /></td>
							<td>
								<select name="special[]">
									<option value=""></option>

									<?php foreach ($sports as $sid => $sport) { ?>

									<option value="<?php echo $sid; ?>"<?php if ($tarif['id_sport_special'] == $sid) echo ' selected'; ?>><?php echo stripslashes($sport['sport']).' '.strip_tags(printSexe($sport['sexe'])); ?></option>

									<?php } ?>

								</select>
							</td>
							<td class="actions">
								<button type="submit" name="edit" value="<?php echo stripslashes($tarif['id']); ?>">
									<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Edit" />
								</button>
																
								<?php if (empty($tarif['teid'])) { ?>

								<button type="submit" name="delete" value="<?php echo stripslashes($tarif['id']); ?>" />
									<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
								</button>

								<?php } ?>

								<input type="hidden" name="ecole_lyonnaise[]" value="1" /> 
								<input type="hidden" name="id[]" value="<?php echo stripslashes($tarif['id']); ?>" />
							</td>
						</tr>

						<?php } ?>

					</tbody>
				</table>
				</form>

				<form method="post">
				<h3>Tarifs des Ecoles <u>Non</u> Lyonnaises</h3>
				<table>
					<thead>
						<tr class="form">
							<td>
								<select name="type[]">
									<?php foreach ($typesTarifs as $label => $nom) { ?>

									<option value="<?php echo $label; ?>"><?php echo $nom; ?></option>

									<?php } ?>

								</select>
							</td>

							<td>
								<select name="for_pompom[]">
									<option value="yes">Oui</option>
									<option value="or" selected></option>
									<option value="no">Non</option>
								</select>
							</td>
							<td>
								<select name="for_cameraman[]">
									<option value="yes">Oui</option>
									<option value="or" selected></option>
									<option value="no">Non</option>
								</select>
							</td>
							<td>
								<select name="for_fanfaron[]">
									<option value="yes">Oui</option>
									<option value="or" selected></option>
									<option value="no">Non</option>
								</select>
							</td>
							<td><input type="text" name="ordre[]" placeholder="Ordre..." /></td>
							<td><input type="text" name="nom[]" placeholder="Nom..." /></td>
							<td><textarea name="description[]" placeholder="Description..."></textarea></td>
							<td>
								<input type="checkbox" name="logement[]" id="form-logement-bis" value="0" />
								<label for="form-logement-bis"></label>
							</td>
							<td><input type="number" step="any" min="0" name="montant[]" placeholder="Montant..." /></td>
							<td>
								<select name="special[]">
									<option value="" selected></option>

									<?php foreach ($sports as $sid => $sport) { ?>

									<option value="<?php echo $sid; ?>"><?php echo stripslashes($sport['sport']).' '.stripslashes(printSexe($sport['sexe'])); ?></option>

									<?php } ?>

								</select>
							</td>
							<td class="actions">
								<button type="submit" name="add">
									<img src="<?php url('assets/images/actions/add.png'); ?>" alt="Add" />
								</button>

								<input type="hidden" name="ecole_lyonnaise[]" value="0" /> 
								<input type="hidden" name="id[]" />
							</td>
						</tr>

						<tr>
							<th style="width:200px">Participants</th>
							<th style="width:70px"><small>Pompom</small></th>
							<th style="width:70px"><small>Caméraman</small></th>
							<th style="width:70px"><small>Fanfaron</small></th>
							<th>Ordre</th>
							<th>Nom</th>
							<th>Description</th>
							<th>Logement</th>
							<th>Montant</th>
							<th><small>Sport Spécial</small></th>
							<th class="actions">Actions</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($tarifs_nonlyonnais)) { ?> 

						<tr class="vide">
							<td colspan="11">Aucun tarif</td>
						</tr>

						<?php } foreach ($tarifs_nonlyonnais as $tarif) { ?>

						<tr class="form">
							<td>
								<select name="type[]">
									
									<?php foreach ($typesTarifs as $label => $nom) { ?>

									<option value="<?php echo $label; ?>"<?php if ($tarif['type'] == $label) echo ' selected'; ?>><?php echo $nom; ?></option>

									<?php } ?>

								</select>
							</td>

							<td>
								<select name="for_pompom[]">
									<option value="yes"<?php if ($tarif['for_pompom'] == 'yes') echo ' selected'; ?>>Oui</option>
									<option value="or"<?php if (!in_array($tarif['for_pompom'], ['yes', 'no'])) echo ' selected'; ?>></option>
									<option value="no"<?php if ($tarif['for_pompom'] == 'no') echo ' selected'; ?>>Non</option>
								</select>
							</td>
							<td>
								<select name="for_cameraman[]">
									<option value="yes"<?php if ($tarif['for_cameraman'] == 'yes') echo ' selected'; ?>>Oui</option>
									<option value="or"<?php if (!in_array($tarif['for_cameraman'], ['yes', 'no'])) echo ' selected'; ?>></option>
									<option value="no"<?php if ($tarif['for_cameraman'] == 'no') echo ' selected'; ?>>Non</option>
								</select>
							</td>
							<td>
								<select name="for_fanfaron[]">
									<option value="yes"<?php if ($tarif['for_fanfaron'] == 'yes') echo ' selected'; ?>>Oui</option>
									<option value="or"<?php if (!in_array($tarif['for_fanfaron'], ['yes', 'no'])) echo ' selected'; ?>></option>
									<option value="no"<?php if ($tarif['for_fanfaron'] == 'no') echo ' selected'; ?>>Non</option>
								</select>
							</td>

							<td><input type="text" name="ordre[]" value="<?php echo stripslashes((int) $tarif['ordre']); ?>" /></td>
							<td><input type="text" name="nom[]" value="<?php echo stripslashes($tarif['nom']); ?>" /></td>
							<td><textarea name="description[]"><?php echo stripslashes($tarif['description']); ?></textarea></td>
							<td>
								<input type="checkbox" name="logement[]" id="form-logement-<?php echo $tarif['id']; ?>" value="<?php echo $tarif['id']; ?>" <?php if (!empty($tarif['logement'])) echo 'checked '; ?>/>
								<label for="form-logement-<?php echo $tarif['id']; ?>"></label>
							</td>
							<td><input type="number" step="any" min="0" name="montant[]" value="<?php echo sprintf('%.2f', (float) $tarif['tarif']); ?>" /></td>
							<td>
								<select name="special[]">
									<option value=""></option>

									<?php foreach ($sports as $sid => $sport) { ?>

									<option value="<?php echo $sid; ?>"<?php if ($tarif['id_sport_special'] == $sid) echo ' selected'; ?>><?php echo stripslashes($sport['sport']).' '.strip_tags(printSexe($sport['sexe'])); ?></option>

									<?php } ?>

								</select>
							</td>
							<td class="actions">
								<button type="submit" name="edit" value="<?php echo stripslashes($tarif['id']); ?>">
									<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Edit" />
								</button>
								
								<?php if (empty($tarif['teid'])) { ?>
							
								<button type="submit" name="delete" value="<?php echo stripslashes($tarif['id']); ?>" />
									<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
								</button>

								<?php } ?>

								<input type="hidden" name="ecole_lyonnaise[]" value="0" /> 
								<input type="hidden" name="id[]" value="<?php echo stripslashes($tarif['id']); ?>" />
							</td>
						</tr>

						<?php } ?>

					</tbody>
				</table>
				</form>


				<script type="text/javascript">
				$(function() {
					$speed =  <?php echo APP_SPEED_ERROR; ?>;

					$analysis = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			                event.preventDefault();
			              	$parent = elem.parent().parent();
			              	$first = $parent.children('td:first');
			  				$participants = $first.children('select');
			  				$for_pompom = $first.next().children('select');
			  				$for_cameraman = $first.next().next().children('select');
			  				$for_fanfaron = $first.next().next().next().children('select');
			  				$ordre = $first.next().next().next().next().children('input');
			  				$nom = $first.next().next().next().next().next().children('input');
			  				$description = $first.next().next().next().next().next().next().children('textarea');
			  				$logement = $first.next().next().next().next().next().next().next().children('textarea');
			  				$montant = $first.next().next().next().next().next().next().next().next().children('input');
			  				$special = $first.next().next().next().next().next().next().next().next().next().children('select');

			                if ($.inArray($participants.val(), ['<?php echo implode(array_keys($typesTarifs), "', '"); ?>']) < 0)
			                	$participants.addClass('form-error').removeClass('form-error', $speed).focus();

			                if ($.inArray($for_pompom.val(), ['yes', 'or', 'no']) < 0)
			                	$for_pompom.addClass('form-error').removeClass('form-error', $speed).focus();

			                if ($.inArray($for_cameraman.val(), ['yes', 'or', 'no']) < 0)
			                	$for_cameraman.addClass('form-error').removeClass('form-error', $speed).focus();

			                if ($.inArray($for_fanfaron.val(), ['yes', 'or', 'no']) < 0)
			                	$for_fanfaron.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$ordre.val().trim() ||
			                	$ordre.val() < 0 ||
			                	Math.floor($ordre.val()) != $ordre.val())
			                	$ordre.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$nom.val().trim())
			                	$nom.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$description.val().trim())
			                	$description.addClass('form-error').removeClass('form-error', $speed).focus();

			                if (!$.isNumeric($montant.val()) ||
			                	$montant.val() < 0)
			                	$montant.addClass('form-error').removeClass('form-error', $speed).focus();

			                if ($special.val() && (
				                	!$.isNumeric($special.val()) ||
				                	$special.val() < 0 ||
				                	Math.floor($special.val()) != $special.val()))
			                	$special.addClass('form-error').removeClass('form-error', $speed).focus();

			                if ($.inArray($for_pompom.val(), ['yes', 'or', 'no']) >= 0 &&
			                	$.inArray($for_cameraman.val(), ['yes', 'or', 'no']) >= 0 &&
			                	$.inArray($for_fanfaron.val(), ['yes', 'or', 'no']) >= 0 &&
			                	$.inArray($participants.val(), ['<?php echo implode(array_keys($typesTarifs), "', '"); ?>']) >= 0 &&
			                	$ordre.val().trim() &&
			                	$ordre.val() >= 0 &&
			                	Math.floor($ordre.val()) == $ordre.val() &&
			                	$nom.val().trim() &&
			                	$description.val().trim() &&
			                	$.isNumeric($montant.val()) &&
			                	$montant.val() >= 0 && (
			                		!$special.val() || 
				                	$.isNumeric($special.val()) &&
				                	$special.val() >= 0 &&
				                	Math.floor($special.val()) == $special.val()))
			                	$parent.children('.actions').children('button:first-of-type').unbind('click').click();   
			           
			            }
			        };

					$('td input[type=text], td input[type=number], td input[type=checkbox], td select, td.actions button:first-of-type').bind('keypress', function(event) {
						$analysis($(this), event, false) });
					$('td.actions button:first-of-type').bind('click', function(event) {
						$analysis($(this), event, true) });	

					document.onselectstart = function() { return false; };


			   	});
				</script>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
