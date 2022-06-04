<?php $title = "Drive LBR - login"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/login.css\">" ?>
<?php $scripts = "" ?>

<?php $banner_menu = "" ?>

<?php ob_start(); ?>

<article id="login">

    <form action="index.php?action=login" method="post">
        <div>
            <?= $error ?>
        </div>
        <div id="login-email">
            <label for="email-field">Entrer votre adresse email : </label>
            <input type="email" name="email" id="email-field" required>
        </div>
        <div id="login-password">
            <label for="password-field">Entrer votre mot de passe : </label>
            <input type="password" name="password" id="password-field" required>
            <a title="Accéder à la page pour réinitialiser votre mot de passe" href="index.php?action=recoverPassword">Mot de passe oublié</a>
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