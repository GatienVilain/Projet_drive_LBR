<?php $title = "Drive LBR - Récupération de mot de passe"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/form.css\">" ?>
<?php $scripts = "" ?>

<?php $banner_menu = "" ?>

<?php ob_start(); ?>

<article>

    <form action="index.php?action=sendRecoveryEmail" method="post" id="form">
        <div>
            <?= $error ?>
        </div>
        <div class="field">
            <label for="email-field">Entrer votre adresse email : </label>
            <input type="email" name="email" id="email-field" required>
        </div>
        <button type="submit">Envoyer un email de récupération</button>
    </form>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>