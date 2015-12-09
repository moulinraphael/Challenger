<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/admin/statistiques/frais.php ******************/
/* Template des frais sur les Ecoles ***********************/
/* *********************************************************/
/* Dernière modification : le 16/02/15 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/admin/_header_admin.php';

?>				
				<style>
				td:not(:first-child) { text-align: center;}
				</style>
			
				<h2>Frais des Ecoles</h2>

				<table>
					<thead>
						<tr>
							<th>Nom</th>
							<th>Participants</th>
							<th>Retards</th>
							<th>Malus %</th>
							<th>Participation</th>
							<th>CodeBar</th>
							<th>Frais</th>
							<th>Total</th>
							<th>Payé</th>
							<th>Restant</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!count($ecoles)) { ?> 

						<tr class="vide">
							<td colspan="10">Aucune école</td>
						</tr>

						<?php } foreach ($ecoles as $ecole) { ?>

						<tr>
							<td><?php echo stripslashes($ecole['nom']); ?></td>
							<td><?php echo $ecole['quota_inscriptions']; ?></td>
							<td><?php echo $ecole['quota_retards']; ?></td>
							<td><?php echo $ecole['malus']; ?> %</td>
							<td><?php echo printMoney($ecole['sum_tarifs']); ?></td>
							<td><?php echo printMoney($ecole['sum_recharges']); ?></td>
							<td><?php echo printMoney($ecole['sum_retards'] * $ecole['malus'] / 100); ?></td>
							<td><b><?php echo printMoney($ecole['sum_tarifs'] + $ecole['sum_recharges'] + $ecole['sum_retards'] * $ecole['malus'] / 100); ?></b></td>
							<td><?php echo printMoney($ecole['sum_paiements']); ?></td>
							<td><?php echo printMoney($ecole['sum_tarifs'] + $ecole['sum_recharges'] + $ecole['sum_retards'] * $ecole['malus'] / 100 - $ecole['sum_paiements']); ?></td>
						</tr>

						<?php } ?>

					</tbody>
				</table>


<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
