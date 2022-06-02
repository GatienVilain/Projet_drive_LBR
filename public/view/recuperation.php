<?php $title = "Drive LBR - Récupération de mot de passe"; ?>
<!-- <?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/formulaire.css\">" ?> -->
<?php $scripts = "" ?>

<?php $banner_menu = "" ?>

<?php ob_start(); ?>

<article>

    <form action="" method="post" id="formulaire">
        <div>
            <?= $info ?>
        </div>
        <div class="champ">
            <label for="champ-email">Entrer votre adresse email : </label>
            <input type="email" name="email" id="champ-email" required>
        </div>
        <button type="submit">Envoyer un email de récupération</button>
    </form>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>