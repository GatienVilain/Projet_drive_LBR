<?php $title = "Drive LBR"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/usersmoderation.css\">" ?>
<?php $scripts = "" ?>

<?php require('public/view/banner-menu.php'); ?>

<?php ob_start(); ?>

<!-- Content -->
<article>

	<div class = toolbar>
		<h2>Utilisateurs :</h2>
		<div class = groupe2>
			<button title="Trier les fichiers par ordre alphabétique" onclick = "">A-Z</button>
			<p>Rôle</p>
		</div>
	</div>

	<?= $userTable ?>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>