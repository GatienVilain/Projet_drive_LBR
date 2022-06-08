<?php $title = "Drive LBR - login"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/login.css\">" ?>
<?php $scripts = "" ?>

<?php require('public/view/banner-menu.php'); ?>

<?php ob_start(); ?>

<script src="C:\wamp64\www\homePage.js"></script>
<link href="C:\wamp64\www\homePage.css" rel="stylesheet">

<div class = toolbar>
<div class = groupe1>
<button title="Trier les fichiers par ordre alphabétique" onclick = "">A-Z</button>
<button title="Sélectionner des filtres" onclick = "">Filtres</button>
<button title="Envoyer un fichier sur le serveur" onclick="">Importer</button>
</div>

<div class = groupe2>
<button title="Trier les fichiers par date d'ajout" onclick="">Date d'ajout</button>
</div>
</div>
<div class="gallery">

<?php 

foreach ($files as $values) {
	echo $values->preview();
}
?>

</div>
<?php require("banner-storage.php");?>
<?php $content = ob_get_clean(); ?>


<?php require("layout.php"); ?>