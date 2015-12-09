<?php

/* *********************************************************/
/* Challenger V3 : Gestion de l'organisation du Challenge **/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/_footer.php ***********************************/
/* Bas de page *********************************************/
/* *********************************************************/
/* Dernière modification : le 18/02/15 *********************/
/* *********************************************************/

?>

			</div>

			<footer class="noprint">
				©Challenger V3 - <a href="<?php url('contact'); ?>">Contacter l'équipe du Challenge Centrale Lyon</a> - 
				<a href="<?php url('classement'); ?>">Classement</a> - 
				<a href="<?php url('admin'); ?>">Administration</a> / <a href="<?php url('ecole'); ?>">Ecoles</a>
			</footer>
		</div>

		<script type="text/javascript">
		$(function() {
			$('.nojs').css('display', 'block');
			
			<?php
			if (!empty($_SESSION['admin']) ||
				!empty($_SESSION['ecole']) &&
				!empty($_SESSION['vp'])) { 
			?>

			setTimeout(function() { window.location.replace("<?php url('ecole'); ?>"); }, 1000 * <?php echo APP_SESSION_MAX_TIME; ?>);

			<?php } ?>
			
		});
		</script>
	</body>
</html>