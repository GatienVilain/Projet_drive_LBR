
<?php ob_start(); ?>

<button title="Ouvrir les options de modération des utilisateurs" href="#">Modérer</button>
<div>
    <a title="Accéder à la page de gestion des utilisateurs" href="index.php?action=usersmoderation">Utilisateurs</a>
    <a title="Accéder au journal de bord, pour voir l’historique des modifications" href="index.php?action=history">Journal de bord</a>
</div>

<?php $admin_navbar = ob_get_clean(); ?>