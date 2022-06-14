
<?php ob_start(); ?>

<button title="Ouvrir la barre de navigation" id="navbar-button"></button>
<nav id="navbar">
    <a title="Se déconnecter et revenir à la page de connexion" href="index.php?action=logout">Déconnexion</a>
    <a title="Accéder au menu principal" href="index.php">Home</a>
    <a title="Voir la page de mon profil et modifier mes informations" href="index.php?action=profile">Profil</a>
    <a title="Accéder à la corbeille, pour restaurer ou supprimer définitivement les fichiers supprimer" href="index.php?action=basket">Corbeille</a>
    <!-- Version mobile -->
    <a title="Ajouter un fichiers" href="#" class="mobile">Importer</a>

    <!-- Pour les administrateurs uniquement -->
    <?= $admin_navbar ?>
</nav>

<?php $banner_menu = ob_get_clean(); ?>