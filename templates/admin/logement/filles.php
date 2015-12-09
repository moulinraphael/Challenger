<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/logement/filles.php *********************/
/* Template des filles logées ******************************/
/* *********************************************************/
/* Dernière modification : le 16/02/15 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Liste des Filles logées </h2>
				
				<center>
					<form method="get">
						<fieldset>
							<select name="ecole" style="width:300px" onchange="$(this).parent().parent().submit();">
								<option value="" disabled <?php if (!isset($_GET['ecole']) || !in_array($_GET['ecole'], array_keys($ecoles)))
									echo 'selected'; ?>>Choisissez une école</option>

								<?php foreach ($ecoles as $id => $ecole) { ?>

								<option value="<?php echo $id; ?>" <?php if (!empty($_GET['ecole']) && $_GET['ecole'] == $id) 
									echo ' selected'; ?>><?php echo stripslashes($ecole['nom']); ?></option>

								<?php } ?>

							</select>
							<input type="submit" class="success" value="Choisir" />
						</fieldset>
					</form>
				</center>

				<?php if (empty($_GET['ecole']) || !in_array($_GET['ecole'], array_keys($ecoles))) { ?>

				<div class="alerte alerte-info">
					<div class="alerte-contenu">
						Veuillez choisir l'école concernant laquelle vous voulez définir les chambres de chacune des participantes.
					</div>
				</div>

				<?php }  else {  ?> 
				
				<br />
				<br />
				<table>
					<thead>
						<tr>
							<td colspan="5">
								<center>Filles à loger :  <b><?php echo count($filles); ?></b>
								</center>
							</td>
						</tr>

						<tr>
							<th style="width:150px !important">Chambre</th>
							<th>Sport</th>
							<th>Nom</th>
							<th>Prenom</th>
							<th style="width:200px !important">Téléphone</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($filles)) { ?> 

						<tr class="vide">
							<td colspan="5">Aucune fille</td>
						</tr>

						<?php } else foreach ($filles as $fille) {
							$numero = !empty($fille['cid']) ? sprintf('%s%d%02d', $fille['batiment'], $fille['etage'], $fille['numero']) : '';
						?>

						<tr class="form">
							<td style="width:150px !important">
								<input style="background-color:<?php echo empty($fille['cid']) ? 'transparent' : colorChambre($numero); ?>" type="text" class="chambre-auto" value="<?php echo $numero; ?>" />
								<input type="hidden" class="chambre-hidden" value="<?php echo $numero; ?>" />
								<input type="hidden" class="chambre-pid" value="<?php echo $fille['id']; ?>" />
							</td>
							<td><div><?php echo empty($fille['sid']) ? '<i>Sans sport</i>' : (stripslashes($fille['sport']).' '.printSexe($fille['sexe'])); ?></div></td>
							<td><div><?php echo stripslashes(strtoupper($fille['nom'])); ?></div></td>
							<td><div><?php echo stripslashes($fille['prenom']); ?></div></td>
							<td><input class="chambre-telephone" type="text" value="<?php echo stripslashes($fille['telephone']); ?>" /></td>
							
						</tr>

						<?php } ?>

					</tbody>
				</table>


				<br />
				<form method="post" id="ajout_amie">
					<center>
						<input type="submit" class="success" value="Ajouter une amie sans logement" />
					</center>
				</form>
				<br />
				<br />
				<table>
					<thead>
						<tr>
							<td colspan="6">
								<center>Amies sans logement, mais logées :  <b><?php echo count($amies_logees); ?></b>
								</center>
							</td>
						</tr>

						<tr>
							<th style="width:150px !important">Chambre</th>
							<th>Sport</th>
							<th>Nom</th>
							<th>Prenom</th>
							<th>Téléphone</th>
							<th>Actions</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($amies_logees)) { ?> 

						<tr class="vide">
							<td colspan="6">Aucune amie logée</td>
						</tr>

						<?php } else foreach ($amies_logees as $fille) {
							$numero = !empty($fille['cid']) ? sprintf('%s%d%02d', $fille['batiment'], $fille['etage'], $fille['numero']) : '';
						?>

						<tr class="form">
							<td style="width:150px !important">
								<input style="background-color:<?php echo empty($fille['cid']) ? 'transparent' : colorChambre($numero); ?>" type="text" readonly value="<?php echo $numero; ?>" />
							</td>
							<td><div><?php echo empty($fille['sid']) ? '<i>Sans sport</i>' : (stripslashes($fille['sport']).' '.printSexe($fille['sexe'])); ?></div></td>
							<td><div><?php echo stripslashes(strtoupper($fille['nom'])); ?></div></td>
							<td><div><?php echo stripslashes($fille['prenom']); ?></div></td>
							<td><div><?php echo stripslashes($fille['telephone']); ?></div></td>
							<td class="actions"><a href="?ecole=<?php echo $_GET['ecole']; ?>&amp;del=<?php echo $fille['id']; ?>"><img src="<?php url('assets/images/actions/delete.png'); ?>" alt="" /></a></td>
							
						</tr>

						<?php } ?>

					</tbody>
				</table>


				<div id="modal-ajout-amie" class="modal">
					<form method="post" action="<?php url('admin/module/logement/filles?ecole='.$_GET['ecole']); ?>">
						<fieldset>
							<legend>Ajout d'une amie sans logement</legend>

							<label for="form-chambre" class="needed">
								<span>Chambre</span>
								<input id="form-chambre" style="background-color:transparent" type="text" class="chambre-auto" value="" data-amies="true" />
								<input type="hidden" class="chambre-hidden" value="" name="chambre" />
								<input type="hidden" class="chambre-pid" value=""  />
							</label>

							<label for="form-fille" class="needed">
								<span>Fille</span>
								<select id="form-fille" name="fille">
									<option value="" disabled selected>Choisissez une fille</option>

									<?php foreach ($filles_non_logees as $fille) { ?>

									<option value="<?php echo $fille['id']; ?>"><?php echo stripslashes(strtoupper($fille['nom']).' '.$fille['prenom']); ?></option>

									<?php } ?>

								</select>
							</label>

							<center>
								<input type="submit" class="success" value="Ajouter l'amie" name="add_amie" />
							</center>
						</fieldset>
					</form>
				</div>


				<script type="text/javascript">
			   	$(function() {
			    	$speed =  <?php echo APP_SPEED_ERROR; ?>;

			        $analysisAjout = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			              	$parent = elem.children('fieldset');
			              	$first = $parent.children('label').first();
			  				$chambre = $first.children('input').first(); //chambre-hidden
			  				$fille = $first.next().children('select');
			  				$erreur = false;
			  				
			                if (!$chambre.next().val().trim()) {
			                	$erreur = true;
			                	$chambre.addClass('form-error-important').removeClass('form-error-important', $speed).focus();
			                }

			                if (!$fille.val().trim()) {
			                	$erreur = true;
			                	$fille.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($erreur)
			                	event.preventDefault();   
			           
			            }
			        };

					$('#modal-ajout-amie form').bind('submit', function(event) { $analysisAjout($(this), event, true); });
					$('form#ajout_amie').bind('submit', function(e) { e.preventDefault();
						$('#modal-ajout-amie').modal({onClose: function () {
							//Fix car l'autocomplete ne se montre pas après avoir fermé puis réouvert le modal
							//Donc on actualise la page
							window.location.href="";
						}}); });

			    	var canSearch = false;
			    	var onlyOnEnter = false;
				    $(".chambre-auto").autocomplete({
				        source: function( request, response ) {
							var $me = this.element;
							$.ajax({
								url: "<?php url('admin/module/logement/filles?ajax'); ?>",
							  	method: "POST",
							  	cache: false,
								dataType: "json",
								data:{
									amies: typeof $me.data('amies') === 'undefined' ? 0 : 1,
									filtre: request.term, 
									chambre: $me.parent().children('.chambre-hidden').val()},
								success: function(data) {
									response(data);
								}
							});
						},
				        minLength:0,
				        select: function(e, ui) {
				            e.preventDefault();

				            $(this).parent().children('.chambre-hidden').val(ui.item.numero).trigger('change');
				            $(this).val(ui.item.numero);
				            $(this).css('background-color', ui.item.color);
				            $(':focus').blur();
				          
				            if (typeof $(this).data('amies') === 'undefined') {
				  				$.ajax({
				  					url: "<?php url('admin/module/logement/filles?maj'); ?>",
				  					method: "POST",
								  	cache: false,
				  					data: {
				  						pid: $(this).parent().children('.chambre-pid').val(),
				  						chambre: ui.item.id
				  					}
				  				});
				  			}
				        },
				        search: function (e, ui) {
				        	var canTempSearch = canSearch;
				        	canSearch = false;
				        	return !onlyOnEnter || onlyOnEnter && canTempSearch;
				        }
				    }).bind('keyup', function(e) {
				    	if (e.keyCode == 13) {
				    		canSearch = true;
				    		$(this).autocomplete("search", $(this).val());
				    	}
				    }).focus(function(){
				    	if (!onlyOnEnter)
				    		$(this).autocomplete("search");        
			        }).blur(function() {
			        	$(this).val($(this).parent().children('.chambre-hidden').val());
			        });

			        $('.chambre-telephone').change(function(event) {
						$parent = $(this).parent().parent();
						$first = $parent.children('td:first');
						$pid = $first.children('input.chambre-pid');
						$telephone = $(this);

		  				$.ajax({
		  					url: "<?php url('admin/module/logement/filles?maj'); ?>",
		  					method: "POST",
						  	cache: false,
		  					data: {
		  						pid: $pid.val(),
		  						telephone: $telephone.val()
		  					}
		  				});
					}).bind('keyup', function() { $(this).change(); });
			    });
				</script>

				<?php } ?>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
