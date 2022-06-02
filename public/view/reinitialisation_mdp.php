<?php $title = "Drive LBR - Changement de mot de passe"; ?>
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
            <label for="champ-password">Entrer le nouveau mot de passe : </label>
            <input type="password" name="password" id="champ-password" required>
        </div>
        <div class="champ">
            <label for="champ-password-conf">Confirmer votre mot de passe :</label>
            <input type="password" name="confirmation_password" id="champ-password-conf" required>
        </div>
        <button type="submit">Valider</button>
    </form>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>