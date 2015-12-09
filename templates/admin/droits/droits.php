<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/droits/droits.php ***********************/
/* Template de la gestion des droits ***********************/
/* *********************************************************/
/* Dernière modification : le 24/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
			
				<h2>Gestion des Droits</h2>


				<form method="post">
					<table>
						<thead>
							<tr>
								<th>Admin</th>

								<?php foreach ($modulesAdmin as $module => $titre) { ?>

								<th class="vertical"><span><?php echo $module; ?></span></th>

								<?php } ?>

							</tr>
						</thead>

						<tbody>

							<?php if (!count($admins)) { ?> 

							<tr class="vide">
								<td colspan="<?php echo 1 + count($modulesAdmin); ?>">Aucun administrateur</td>
							</tr>

							<?php } foreach ($admins as $admin) { ?>

							<tr class="form">
								<td><center><?php echo stripslashes(strtoupper($admin['nom']).' '.$admin['prenom']); ?></center></td>
								
								<?php foreach ($modulesAdmin as $module => $titre) { ?>

								<td class="vertical">

									<?php if ($_SESSION['admin']['user'] != $admin['id']) { ?>

									<input type="submit" name="droit_<?php echo $admin['id'].'_'.$module; ?>" class="<?php echo in_array($module, $admin['modules']) ? 'delete" value="-' : 'success" value="+'; ?>" />

									<?php } ?>

								</td>

								<?php } ?>

							</tr>

							<?php } ?>

						</tbody>
					</table>
				</form>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
