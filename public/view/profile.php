<?php $title = "Drive LBR - profil"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/profile.css\">" ?>
<?php $scripts = "" ?>

<?php require('public/view/banner-menu.php'); ?>

<?php ob_start(); ?>

<!-- Page de Profil -->
<article id="profile">

<!-- Composant d’information du profil -->
    <section id="profile-information">
        <h3>
            <!-- remplacer par le nom du profil utilisateur -->
            <?= $name ?>
        </h3>
        <p class="role">
            <!-- ȑemplacer par le rôle de l’utilisateur -->
            <?= $role ?>
        </p>
        <p class="date" >Inscrit depuis le
                <!-- `Remplacer par de la date d’inscription de l’utilisateur -->
                <?= $registration_date ?>
        </p>
        <form action="index.php?action=changeDescription" method="post">
            <textarea id="profile-description" name="description"  maxlength="256" required><?= $description ?></textarea>
            <button type="submit">Modifier</button>
        </form>
    </section>

<!-- Changer mot de passe -->
    <section id="change-password-section">
        <form action="index.php?action=changePasswordProfile" method="post">
            <div>
                <?= $error ?>
            </div>
            <div id="old-password">
                <label for="old-password-field">Ancien mot de passe : </label>
                <input type="password" name="old_password" id="old-password-field" required>
            </div>
            <div class="field">
                <label for="new-password-field">Entrer le nouveau mot de passe : </label>
                <input type="password" name="password" id="new-password-field" required>
            </div>
            <div class="field">
                <label for="confirmation-password-field">Confirmer votre mot de passe :</label>
                <input type="password" name="confirmation_password" id="confirmation-password-field" required>
            </div>
            <button type="submit">Valider</button>
        </form>
    </section>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>