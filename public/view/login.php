<?php $title = "Drive LBR - login"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/login.css\">" ?>
<?php $scripts = "" ?>

<?php $banner_menu = "" ?>

<?php ob_start(); ?>

<article id="login">

    <form action="" method="post">
        <div>
            <?= $erreur ?>
        </div>
        <div id="login-email">
            <label for="champ-email">Entrer votre adresse email : </label>
            <input type="email" name="email" id="champ-email" required>
        </div>
        <div id="login-password">
            <label for="champ-password">Entrer votre mot de passe : </label>
            <input type="password" name="password" id="champ-password" required>
            <a title="Accéder à la page pour réinitialiser votre mot de passe" href="index.php?page=recuperation_mdp">Mot de passe oublié</a>
        </div>
        <div id="login-checkbox">
            <label for="checkbox">Rester connecté </label>
            <input type="checkbox" name="remember" id="checkbox">
        </div>
        <Button type="submit">S’authentifier</button>
    </form>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>