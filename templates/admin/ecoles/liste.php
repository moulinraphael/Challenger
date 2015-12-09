<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/ecoles/liste.php ************************/
/* Template de la liste du module des Ecoles ***************/
/* *********************************************************/
/* Dernière modification : le 21/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<h2>Liste des Ecoles</h2>

				<?php if (isset($delete)) { ?>

				<div class="alerte alerte-success">
					<div class="alerte-contenu">
						L'école et toutes ses données ont bien été supprimées
					</div>
				</div>

				<?php } else if (isset($empty)) { ?>

				<div class="alerte alerte-success">
					<div class="alerte-contenu">
						L'école a bien été vidée de ses données, mais pas supprimée.
					</div>
				</div>

				<?php } ?>

				<form method="post" id="ajout_ecole">
					<center>
						<input type="submit" class="success" value="Ajouter une école" />
					</center>
				</form>
				<br />

				<table>
					<thead>
						<tr>
							<th>Nom</th>
							<th>Login</th>
							<th style="width:150px"><small>Inscrip. / Quota</small></th>
							<th>Etat</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($ecoles)) { ?> 

						<tr class="vide">
							<td colspan="4">Aucune école</td>
						</tr>

						<?php } foreach ($ecoles as $ecole) { ?>

						<tr class="clickme" onclick="window.location.replace('<?php url('admin/module/ecoles/'.$ecole['id']); ?>');">
							<td><?php echo stripslashes($ecole['nom']); ?></td>
							<td><?php echo stripslashes($ecole['login']); ?></td>
							<td><center><?php echo $ecole['quota_inscriptions'].' / <b>'.$ecole['quota_total'].'</b>'; ?></center></td>
							<td><?php echo printEtatEcole($ecole['etat_inscription']); ?></td>
						</tr>

						<?php } ?>

					</tbody>
				</table>

				<div id="modal-ajout-ecole" class="modal">
					<form method="post">
						<fieldset>
							<legend>Ajout d'une école</legend>

							<label for="form-nom" class="needed">
								<span>Nom</span>
								<input type="text" name="nom" id="form-nom" value="" />
							</label>

							<center>
								<input type="submit" class="success" value="Ajouter l'école" name="add_ecole" />
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
			  				$nom = $first.children('input');
			  				$erreur = false;
			  				
			                if (!$nom.val().trim()) {
			                	$erreur = true;
			                	$nom.addClass('form-error').removeClass('form-error', $speed).focus();
			                }

			                if ($erreur)
			                	event.preventDefault();   
			           
			            }
			        };

					$('#modal-ajout-ecole form').bind('submit', function(event) { $analysisAjout($(this), event, true); });
					$('form#ajout_ecole').bind('submit', function(e) { e.preventDefault(); $('#modal-ajout-ecole').modal(); });
				});
				</script>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
