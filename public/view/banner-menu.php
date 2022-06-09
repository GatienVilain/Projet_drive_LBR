
<?php ob_start(); ?>

<button title="Ouvrir la barre de navigation" id="navbar-button"></button>
<nav id="navbar">
    <a title="Se déconnecter et revenir à la page de connexion" href="index.php?action=logout">Déconnexion</a>
    <a title="Accéder au menu principal" href="index.php">Home</a>
    <a title="Voir la page de mon profil et modifier mes informations" href="index.php?action=profile">Profil</a>
    <a title="Accéder à la corbeille, pour restaurer ou supprimer définitivement les fichiers supprimer" href="#">Corbeille</a>
    <!-- Version mobile -->
    <a title="Ajouter un fichiers" href="#" class="mobile">Importer</a>
    <!-- Pour les administrateurs uniquement -->
    <button title="Ouvrir les options de modération des utilisateurs" href="#">Modérer</button>
    <div>
        <a title="Accéder à la page de gestion des utilisateurs" href="index.php?action=usersmoderation">Utilisateurs</a>
        <a title="Accéder au journal de bord, pour voir l’historique des modifications" href="index.php?action=history">Journal de bord</a>
    </div>
</nav>

<?php $banner_menu = ob_get_clean(); ?>