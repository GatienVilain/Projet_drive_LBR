<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <!-- Metadata -->
    <title><?= $title ?></title>
    <meta name="author" content="Célestin Captal, Maxime Herbin, Dorian Larouziere, Gatien Vilain">
    <!-- Page Layout-->
    <meta name="theme-color" content="white">
    <meta name="color-scheme" content="normal">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Style Sheets -->
    <link rel="stylesheet" href="public/css/styles.css">
    <?= $stylesheets ?>
</head>

<body>
    <header id="banner">
        <a title="Revenir à la page d’accueil" href="/" class="logo complet">
            <img src="public/images/logoLONGUEURClassic.png" alt="Page d’accueil">
        </a>
        <a title="Revenir à la page d’accueil" href="/" class="logo mobile">
            <img src="public/images/intiniales rogne.png" alt="Page d’accueil">
        </a>

        <!-- Quand on est connecté -->
        <?= $banner_menu ?>
    </header>
    <?= $content ?>
    <!--  Scripts  -->
    <?= $scripts ?>
</body>

</html>