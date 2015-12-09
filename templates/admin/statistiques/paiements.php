<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/statistiques/paiements.php **************/
/* Template des paiements **********************************/
/* *********************************************************/
/* Dernière modification : le 24/01/15 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>
				
				<h2>Liste des Paiements (par Ecole)</h2>


				<?php

				foreach ($paiements as $eid => $paiements_ecole) {

				?>
				
				<h3><?php echo stripslashes($paiements_ecole[0]['nom']); ?></h3>
				
				<table class="table-small">
					<thead>
						<tr>
							<td colspan="4">
								<center>Paiements : <b><?php echo empty($paiements_ecole[0]['paid']) ? 0 : count($paiements_ecole); ?></b></center>
							</td>
						</tr>

						<tr>
							<th>Date</th>
							<th>Montant</th>
							<th>Etat</th>
							<th>Type</th>
						</tr>
					</thead>

					<tbody>

						<?php if (empty($paiements_ecole[0]['paid'])) { ?> 

						<tr class="vide">
							<td colspan="7">Aucun paiement</td>
						</tr>

						<?php } else foreach ($paiements_ecole as $paiement) { ?>

						<tr>
							<td><?php echo printDateTime($paiement['date']); ?></td>
							<td><?php echo printMoney($paiement['montant']); ?></td>
							<td><?php echo printEtatPaiement($paiement['etat']); ?></td>
							<td><?php echo printTypePaiement($paiement['type']); ?></td>
							
						</tr>

						<?php } ?>

					</tbody>
				</table>

				<?php } ?>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
