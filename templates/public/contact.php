<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/public/contact.php ****************************/
/* Template de la page de contact **************************/
/* *********************************************************/
/* Dernière modification : le 12/12/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header_nomenu.php';

?>
				<form class="form-table" method="post">
					<fieldset>
						<h3>Nos adresses</h3>

						<div>
							<div class="bloc">
								<h4>Par mail</h4>
								Pour les écoles, merci de contacter en priorité le VP Sport qui voues est affecté. Il est affiché dans le récapitulatif de votre espace. <br />
								Vous pouvez nous contacter par mail à l'adresse suivante : 
								<a href="mailto:<?php echo APP_EMAIL_CHALLENGE; ?>"><?php echo APP_EMAIL_CHALLENGE; ?></a>
							</div>

							<div class="bloc">
								<h4>Par courrier</h4>
								Vous pouvez nous écrire à l'adresse suivante :<br />
								<b>USEECL Challenge<br />
								Ecole Centrale de Lyon<br />
								36 avenue Guy de Collongue<br />
								69134 ECULLY CEDEX</b>
							</div>
						</div>

						<div>
							<div class="bloc">
								<h4>Sur notre site web</h4>
								Vous trouverez un grand nombre d'informations sur notre site web dédié au Challenge :
								<a href="<?php echo APP_URL_CHALLENGE; ?>">Challenge Grandes Ecoles</a>
							</div>

							<div class="bloc">
								<h4>Sur Facebook</h4>
								Vous pouvez nous rejoindre sur
								<a href="<?php echo APP_URL_FACEBOOK; ?>">notre page Facebook</a>.
							</div>
						</div>
					</fieldset>

					<fieldset>
						<h3>Contacts de l'équipe Challenge</h3>

						<div class="bloc">
						
							<?php foreach ($contacts as $i => $contact) { ?>

							<div class="clearfix contact">
								<img src="<?php echo $contact['auth_type'] == 'cas' ? (URL_API_ECLAIR.'?type=photo&login='.$contact['login']) : 
									(url('assets/images/themes/'.
									(file_exists(DIR.'assets/images/themes/'.$contact['login'].'.jpg') ?
									$contact['login'] : 'unknown'), false, false).'.jpg'); ?>" alt="<?php echo $contact['login']; ?>" />

								<h4><?php echo stripslashes(strtoupper($contact['nom']).' '.$contact['prenom']); ?></h4>
								<i><?php echo stripslashes($contact['poste']); ?></i><br /><br />
								<a href="mailto:<?php echo $contact['email']; ?>"><?php echo $contact['email']; ?></a><br />
							</div>

							<?php if ($i + 1 == (int) (count($contacts) / 2)) { ?>

						</div>
						<div class="bloc">
							
							<?php } else { ?>

							<br />

							<?php } }  ?>

						</div>

					</fieldset>
				</form>

				
<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
