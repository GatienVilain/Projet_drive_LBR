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

    <section id="change-password-section">

    <?php
        foreach ($table as $categorie)
        {
            echo array_keys($categorie);
            foreach ($categorie as $tag)
            {
                if ($tag["ecriture"])
                {
                    echo $tag["nom_tag"];
                }
            }
        }
        foreach ($table as $categorie)
        {
            echo array_keys($categorie);
            foreach ($categorie as $tag)
            {
                if ($tag["lecture"])
                {
                    echo $tag["nom_tag"];
                }
            }
        }
    ?>

    </section>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>