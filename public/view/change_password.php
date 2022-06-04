<?php $title = "Drive LBR - Changement de mot de passe"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/form.css\">" ?>
<?php $scripts = "" ?>

<?php $banner_menu = "" ?>

<?php ob_start(); ?>

<article>

    <form action="index.php?action=changePassword" method="post" id="form">
        <div>
            <?= $error ?>
        </div>
        <div class="field">
            <label for="password-field">Entrer le nouveau mot de passe : </label>
            <input type="password" name="password" id="password-field" required>
        </div>
        <div class="field">
            <label for="confirmation-password-field">Confirmer votre mot de passe :</label>
            <input type="password" name="confirmation_password" id="confirmation-password-field" required>
        </div>
        <button type="submit">Valider</button>
    </form>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>