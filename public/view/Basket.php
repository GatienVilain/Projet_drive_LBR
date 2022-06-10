<?php $title = "Drive LBR"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/homepage.css\">" ?>
<?php $scripts = "" ?>

<?php require('public/view/banner-menu.php'); ?>

<?php ob_start(); ?>

<!-- Content -->
<script src="public\js\basket.js"></script>

<div class = toolbar>
    <div class = groupe1>
        <button title="Trier les fichiers par ordre alphabétique" onclick = "">A-Z</button>
        <button title="Sélectionner des filtres" onclick = "">Filtres</button>
        <!-- <button title="Envoyer un fichier sur le serveur" onclick="">Importer</button> -->
    </div>

    <div class = groupe2>
        <button title="Trier les fichiers par date de suppression" onclick="">Date de suppression</button>
    </div>
</div>

<div class="gallery">

<?php
    foreach ($files as $values)
    {
        echo $values->preview();
    }
?>

</div>

<?php require('public/view/banner-storage.php'); ?>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>