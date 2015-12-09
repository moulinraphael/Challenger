<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/classement/edition.php ******************/
/* Template de la liste des classements ********************/
/* *********************************************************/
/* Dernière modification : le 17/02/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<h2>Edition des Classements</h2>

				<?php
				if (isset($add) ||
					isset($modify) ||
					!empty($delete)) {
				?>

				<div class="alerte alerte-<?php echo !empty($add) || !empty($modify) || !empty($delete) ? 'success' : 'erreur'; ?>">
					<div class="alerte-contenu">
						<?php
						if (!empty($modify)) echo 'Le classement a bien été édité';
						else if (!empty($delete)) echo 'Le classement a bien été supprimé';
						else if (!empty($add)) echo 'Le classement a bien été ajouté';
						?>
					</div>
				</div>

				<?php } ?>


				<form method="post">
					<table>
						<thead>
							<tr class="form">
								<td>
									<select name="sport[]">
										<option value="">Sport...</option>

										<?php foreach ($sports as $sid => $sport) { ?>

										<option value="<?php echo $sid; ?>" data-coeff="<?php echo $sport['nb_equipes']; ?>"><?php echo stripslashes($sport['sport']).' '.strip_tags(printSexe($sport['sexe'])); ?></option>

										<?php } ?>

									</select>
								</td>
								<td><input type="number" name="coeff[]" value="" min="0" placeholder="Coefficient..." /></td>
								<td>
									<select name="ecole1[]">
										<option value="">1er...</option>

										<?php foreach ($ecoles as $eid => $ecole) { ?>

										<option value="<?php echo $eid; ?>"><?php echo stripslashes($ecole['nom']); ?></option>

										<?php } ?>

									</select>
								</td>
								<td>
									<input type="checkbox" id="form-ex12" name="ex12[]" value="0" />
									<label for="form-ex12"></label>
								</td>
								<td>
									<select name="ecole2[]">
										<option value="">2e...</option>

										<?php foreach ($ecoles as $eid => $ecole) { ?>

										<option value="<?php echo $eid; ?>"><?php echo stripslashes($ecole['nom']); ?></option>

										<?php } ?>

									</select>
								</td>
								<td>
									<input type="checkbox" id="form-ex23" name="ex23[]" value="0" />
									<label for="form-ex23"></label>
								</td>
								<td>
									<select name="ecole3[]">
										<option value="">3e...</option>

										<?php foreach ($ecoles as $eid => $ecole) { ?>

										<option value="<?php echo $eid; ?>"><?php echo stripslashes($ecole['nom']); ?></option>

										<?php } ?>

									</select>
								</td>
								<td>
									<input type="checkbox" id="form-ex3" name="ex3[]" value="0" />
									<label for="form-ex3"></label>
								</td>
								<td>
									<select name="ecole3ex[]">
										<option value="">3e Ex...</option>

										<?php foreach ($ecoles as $eid => $ecole) { ?>

										<option value="<?php echo $eid; ?>"><?php echo stripslashes($ecole['nom']); ?></option>

										<?php } ?>

									</select>
								</td>
								<td class="actions">
									<button type="submit" name="add">
										<img src="<?php url('assets/images/actions/add.png'); ?>" alt="Add" />
									</button>
									<input type="hidden" name="id[]" value="0" />
								</td>
							</tr>

							<tr>
								<th>Sport</th>
								<th style="width:60px">Coefficient</th>
								<th>1er</th>
								<th style="width:60px"><small>Exaequo</small></th>
								<th>2e</th>
								<th style="width:60px"><small>Exaequo</small></th>
								<th>3e</th>
								<th style="width:60px"><small>Exaequo</small></th>
								<th>3e-Ex</th>
								<th class="actions">Actions</th>
							</tr>
						</thead>

						<tbody>

							<?php if (!count($classements)) { ?> 

							<tr class="vide">
								<td colspan="10">Aucun classement</td>
							</tr>

							<?php } foreach ($classements as $classement) { ?>

							<tr class="form">
								<td>
									<div><?php echo stripslashes($classement['sport']).' '.printSexe($classement['sexe']); ?></div>
									<input type="hidden" name="sport[]" value="<?php echo $classement['id_sport']; ?>" />
								</td>
								<td><input type="number" name="coeff[]" value="<?php echo $classement['coeff']; ?>" min="0" /></td>
								<td>
									<select name="ecole1[]">

										<?php foreach ($ecoles as $eid => $ecole) { ?>

										<option value="<?php echo $eid; ?>"<?php if ($classement['id_ecole_1'] == $eid) echo ' selected'; ?>><?php echo stripslashes($ecole['nom']); ?></option>

										<?php } ?>

									</select>
								</td>
								<td>
									<input type="checkbox" id="form-ex12-<?php echo $classement['id_sport']; ?>" name="ex12[]" value="<?php echo $classement['id_sport']; ?>" <?php if (!empty($classement['ex_12'])) echo 'checked '; ?>/>
									<label for="form-ex12-<?php echo $classement['id_sport']; ?>"></label>
								</td>
								<td>
									<select name="ecole2[]">

										<?php foreach ($ecoles as $eid => $ecole) { ?>

										<option value="<?php echo $eid; ?>"<?php if ($classement['id_ecole_2'] == $eid) echo ' selected'; ?>><?php echo stripslashes($ecole['nom']); ?></option>

										<?php } ?>

									</select>
								</td>
								<td>
									<input type="checkbox" id="form-ex23-<?php echo $classement['id_sport']; ?>" name="ex23[]" value="<?php echo $classement['id_sport']; ?>" <?php if (!empty($classement['ex_23'])) echo 'checked '; ?>/>
									<label for="form-ex23-<?php echo $classement['id_sport']; ?>"></label>
								</td>
								<td>
									<select name="ecole3[]">

										<?php foreach ($ecoles as $eid => $ecole) { ?>

										<option value="<?php echo $eid; ?>"<?php if ($classement['id_ecole_3'] == $eid) echo ' selected'; ?>><?php echo stripslashes($ecole['nom']); ?></option>

										<?php } ?>

									</select>
								</td>
								<td>
									<input type="checkbox" id="form-ex3-<?php echo $classement['id_sport']; ?>" name="ex3[]" value="<?php echo $classement['id_sport']; ?>" <?php if (!empty($classement['ex_3'])) echo 'checked '; ?>/>
									<label for="form-ex3-<?php echo $classement['id_sport']; ?>"></label>
								</td>
								<td>
									<select name="ecole3ex[]">
										<option value=""></option>

										<?php foreach ($ecoles as $eid => $ecole) { ?>

										<option value="<?php echo $eid; ?>"<?php if ($classement['id_ecole_3ex'] == $eid) echo ' selected'; ?>><?php echo stripslashes($ecole['nom']); ?></option>

										<?php } ?>

									</select>
								</td>
								<td class="actions">
									<button type="submit" name="edit" value="<?php echo stripslashes($classement['id_sport']); ?>">
										<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Edit" />
									</button>									
									<button type="submit" name="delete" value="<?php echo stripslashes($classement['id_sport']); ?>" />
										<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
									</button>
									<input type="hidden" name="id[]" value="<?php echo stripslashes($classement['id_sport']); ?>" />
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
			  				$sport = $first.children('select');
			  				$coeff = $first.next().children('input');
			  				$ecole1 = $first.next().next().children('select');
			  				$ex12 = $first.next().next().next().children('input');
			  				$ecole2 = $first.next().next().next().next().children('select');
			  				$ex23 = $first.next().next().next().next().next().children('input');
			  				$ecole3 = $first.next().next().next().next().next().next().children('select');
			  				$ex3 = $first.next().next().next().next().next().next().next().children('input');
			  				$ecole3ex = $first.next().next().next().next().next().next().next().next().children('select');
			  				$erreur = false;

			  				$actualise3eEx(elem);

			                if ($sport.length &&
			                	!$sport.val().trim()) {
			                	$erreur = true;
			                	$sport.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$coeff.val().trim()) {
			                	$erreur = true;
			                	$coeff.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$ecole1.val()) {
			                	$erreur = true;
			                	$ecole1.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$ecole2.val()) {
			                	$erreur = true;
			                	$ecole2.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if (!$ecole3.val()) {
			                	$erreur = true;
			                	$ecole3.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($ex3.is(':checked') &&
			                	!$ecole3ex.val().trim()) {
			                	$erreur = true;
			                	$ecole3ex.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($ecole1.val() == $ecole2.val() ||
			                	$ecole1.val() == $ecole3.val() ||
			                	$ecole2.val() == $ecole3.val() ||
			                	$ex3.is(':checked') && (
			                		$ecole1.val() == $ecole3ex.val() ||
			                		$ecole2.val() == $ecole3ex.val() ||
			                		$ecole3.val() == $ecole3ex.val())) {
			                	$erreur = true;
			                	$ecole1.addClass('form-error').removeClass('form-error', $speed).focus();
			                	$ecole2.addClass('form-error').removeClass('form-error', $speed).focus();
			                	$ecole3.addClass('form-error').removeClass('form-error', $speed).focus();
			                	if ($ex3.is(':checked'))
			                	  	$ecole3ex.addClass('form-error').removeClass('form-error', $speed).focus();
			               	}

			                if (!$erreur) {
			                	$('tr.form td :disabled').prop('disabled', false);
			                	$parent.children('.actions').children('button:first-of-type').unbind('click').click();   
			           		}
			            }
			        };

					$('td input[type=text], td input[type=number], td input[type=checkbox], td input[type=password], td select, td.actions button:first-of-type').bind('keypress', function(event) {
						$analysis($(this), event, false) });
					$('td.actions button:first-of-type').bind('click', function(event) {
						$analysis($(this), event, true) });	


					$actualiseCoefficient = function(elem) {
						$first = elem.parent().parent().children('td').first();
						$sport = $first.children('select').first();
						$coeff = $first.next().children('input').first();

						$coeff.val($sport.children('option:selected').data('coeff'));
					};

					$actualise3eEx = function(elem) {
						$first = elem.parent().parent().children('td').first();
						$ex3 = $first.next().next().next().next().next().next().next().children('input').first();
						$ecole3ex = $first.next().next().next().next().next().next().next().next().children('select').first();

						if (!$ex3.is(':checked')) {
							$ecole3ex.prop('disabled', true);
							$ecole3ex.children('option:first-child').prop('selected', true);
						} else
							$ecole3ex.prop('disabled', false);

					};

					$('tr.form td:first-child select').change(function() {
						$actualiseCoefficient($(this));
					});

					$('tr.form td:nth-child(4) input[type=checkbox], tr.form td:nth-child(6) input[type=checkbox], tr.form td:nth-child(8) input[type=checkbox]').change(function() {
						$actualise3eEx($(this));
					});

					$('tr.form td:nth-child(4) input[type=checkbox]').each(function() {
						$actualise3eEx($(this));
					});
				});
				</script>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
