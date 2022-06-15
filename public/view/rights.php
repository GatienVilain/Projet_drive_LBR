<?php $title = "Drive LBR - profil"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/rights.css\">" ?>
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

    <section id="rights-section">
        <form action = "index.php?action=deleteRights&for=<?= $email ?>" method= "post">

            <?php
                $types = array("ecriture","lecture");

                foreach ($types as $type)
                {   ?>
                    <div>
                    <h3>Droits en <?= $type ?></h3>
                    <?php
                    foreach ($table as $key=>$categorie)
                    {   ?>
                        <div>
                        <h4>- <?= $key ?> :</h4>
                        <?php
                        if (!empty($categorie))
                        {
                            foreach ($categorie as $tag)
                            {
                                if ($tag[$type])
                                {   ?>
                                    <span>
                                        <input type="checkbox" name="<?= $type[0] . $tag["id_tag"] ?>">
                                        <label><?= $tag["nom_tag"] ?></label>
                                    </span>
                                    <?php
                                }
                            }
                        }
                        ?>
                        <button>+ Ajouter</button>
                        </div>
                        <?php
                    }
                    ?>
                    </div>
                    <?php
                }
            ?>

            <button type="submit">Supprimer</button>
        </form>
    </section>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>