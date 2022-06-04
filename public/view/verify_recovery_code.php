<?php $title = "Drive LBR - Vérification du mot de passe"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/form.css\">" ?>
<?php $scripts = "" ?>

<?php $banner_menu = "" ?>

<?php ob_start(); ?>

<article>

    <form action="index.php?action=verifyRecoveryCode" method="post" id="form">
        <div>
            <?= $error ?>
        </div>
        <div class="field">
            <label for="verification-code">Entrez le code reçu à d’adresse :</label>
            <input type="number" name="verification_code" id="verification-code" required>
        </div>
        <button type="submit">Valider</button>
    </form>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>