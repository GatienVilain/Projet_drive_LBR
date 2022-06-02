<?php $title = "Drive LBR - Vérification du mot de passe"; ?>
<!-- <?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/formulaire.css\">" ?> -->
<?php $scripts = "" ?>

<?php $banner_menu = "" ?>

<?php ob_start(); ?>

<article>

    <form action="" method="post" id="formulaire">
        <div>
            <?= $erreur ?>
        </div>
        <div class="champ">
            <label for="codeverif">Entrez le code reçu à d’adresse :</label>
            <input type="number" name="codeverif" id="codeverif" required>
        </div>
        <button type="submit">Valider</button>
    </form>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>