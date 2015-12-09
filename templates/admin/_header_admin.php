<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/_header_admin.php ***********************/
/* Haut de page de l'administration ************************/
/* *********************************************************/
/* Dernière modification : le 20/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

			<div class="menus noprint">
				<nav>
					<ul><!--

						--><li class="logout">
							<a href="<?php url('admin/logout'); ?>">Déconnexion</a>
						</li><!--

						--><li>
							<span>Modules</span>
							<ul>

								<?php
								foreach ($modulesAdmin as $_module => $_titre)
									if (in_array($_module, $_SESSION['admin']['privileges']))
										echo '<li><a href="'.url('admin/module/'.$_module, false, false).'">'.$_titre.'</a></li>';
								?>

							</ul>
						</li><!--
						
						<?php if (!empty($module)) { ?>

						--><li>
							<span><i><?php echo $modulesAdmin[$module]; ?></i></span>
							<ul>

								<?php
								foreach ($actionsModule as $_action => $_titre)
									echo '<li><a href="'.url('admin/module/'.$module.'/'.$_action, false, false).'">'.$_titre.'</a></li>';
								?>

							</ul>
						</li><!--

						<?php } ?>

					--></ul>
				</nav>
			</div>

			<div class="main">
