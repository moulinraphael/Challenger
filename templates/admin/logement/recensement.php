<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/logement/recensement.php ****************/
/* Template des bâtiments pour le recensement **************/
/* *********************************************************/
/* Dernière modification : le 19/01/15 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Recensement des Chambres</h2>
				
				<style>
				img { vertical-align: -2px; margin-right:10px;}
				</style>

				<center>

				<?php

				foreach (array('U', 'V', 'T', 'X', 'A', 'B', 'C') as $batiment) {

					if ($batiment == 'A')
						echo '<br />';

				?>

				<a href="<?php url('admin/module/logement/'.$batiment); ?>" class="square">
					<div class="square-titre">Bâtiment <?php echo $batiment; ?></div>
					<div class="square-stats">
						<img src="<?php url('assets/images/actions/active.png'); ?>" alt="" /> Disponible : <b><?php echo $batiments[$batiment]['nb_active']; ?></b><br />
						<img src="<?php url('assets/images/actions/question.png'); ?>" alt="" /> Possiblement : <b><?php echo
							$batiments[$batiment]['nb_chambres'] - $batiments[$batiment]['nb_active'] - $batiments[$batiment]['nb_inactive']; ?></b><br />
						<img src="<?php url('assets/images/actions/inactive.png'); ?>" alt="" /> Non définitif : <b><?php echo $batiments[$batiment]['nb_inactive']; ?></b>
					</div>
				</a>

				<?php } ?>

				</center>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
