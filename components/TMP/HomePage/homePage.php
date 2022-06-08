<?php $title="";?>
<?php $stylesheets=""?>
<?php $scripts=""?>
<?php require("banner-menu.php"); ?>
<?php require("image.php"); ?>
<?php ob_start(); ?>

<?php

require 'auth.php';
forcer_utilisateur_connecter();
?>

<?php// $imagePath = "/images/test.png";
//afficherMiniature($imagePath);?>
<script src="homePage.js"></script>
<link href="homePage.css" rel="stylesheet">

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
// (B) GET LIST OF IMAGE FILES FROM GALLERY FOLDER
$dir = __DIR__ . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "miniature" . DIRECTORY_SEPARATOR;
$images = glob("$dir*.{jpg,jpeg,gif,png,bmp,webp}", GLOB_BRACE);
 
// (C) OUTPUT IMAGES 
foreach ($images as $i) {
  printf("<div class=miniature><div class='image'> <img src='images/miniature/%s' ALT ='%s'/></div> <div class = titre> <p> %s </p> </div></div>", basename($i), basename($i),pathinfo($i, PATHINFO_FILENAME));
}

?>

</div>
<?php require("banner-storage.php");?>
<?php $content = ob_get_clean(); ?>


<?php require("layout.php"); ?>
