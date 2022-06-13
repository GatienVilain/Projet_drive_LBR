<?php $title = "Drive LBR - profil"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/profile.css\">" ?>
<?php $scripts = "" ?>

<?php require('public/view/banner-menu.php'); ?>

<?php ob_start(); ?>

<!-- Page de Profil -->
<article id="profile">



<!-- Changer mot de passe -->
    <section id="change-password-section">
        <form action="index.php?action=addUser" method="post">
            <div>
                <?= $error ?>
            </div>
            <div class="field">
                <label for="name">Nom : </label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="field">
                <label for="first_name">Prénom : </label>
                <input type="text" name="first_name" id="first_name" required>
            </div>
            <div class="field">
                <label for="mail">Mail du nouvel utilisateur : </label>
                <input type="text" name="mail" id="mail" required>
            </div>
            <div class="field">
                <label for="new-password-field">Entrer le nouveau mot de passe : </label>
                <input type="password" name="new-password-field" id="new-password-field" required>
            </div>
            <div class="field">
                <label for="confirmation-password-field">Confirmer votre mot de passe :</label>
                <input type="password" name="confirmation-password-field" id="confirmation-password-field" required>
            </div>
            <div class="field">
                <label for="profile-description">Description : </label>
                <input type="text" name="profile-description" id="profile-description" maxlenght="256"
                    value="<?= $description=''; ?>">

            </div>
            <div>
                <SELECT name="role" size="1">
                    <OPTION>invité
                    <OPTION>admin
                    </SELECT>
</div>
            <button type="submit">Valider</button>
        </form>
    </section>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>