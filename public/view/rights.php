<?php $title = "Drive LBR - profil"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/rights.css\">" ?>
<?php $scripts = '<script type="text/javascript" src="public/js/rights.js"></script>' ?>

<?php require('public/view/banner-menu.php'); ?>
<?php require('public/view/add_rights_menu.php'); ?>

<?php ob_start(); ?>

<!-- Page de Profil -->
<article id="profile">

<!-- Composant d’information du profil -->
    <section id="profile-information">
        <h3>
            <!-- remplacer par le nom du profil utilisateur -->
            <?= $name ?>
        </h3>
        <form action="index.php?action=changeRole&for=<?= $email ?>" method="post" id="change-role">
            <select name="role">

                <?php if ($role == "admin")
                {  ?>

                    <option value="admin" selected>admin</option>
                    <option value="invite">invite</option>

                    <?php
                }
                else {  ?>

                    <option value="admin">admin</option>
                    <option value="invite" selected>invite</option>

                    <?php
                }
                ?>

            </select>
            <button type="submit">🖉 valider</button>
        </form>
        <p class="date" >Inscrit depuis le
                <!-- `Remplacer par de la date d’inscription de l’utilisateur -->
                <?= $registration_date ?>
        </p>
        <form action="index.php?action=changeDescriptionFor&for=<?= $email ?>" method="post">
            <textarea id="profile-description" name="description"  maxlength="256" required><?= $description ?></textarea>
            <button type="submit">Modifier</button>
        </form>
        <form action="index.php?action=changePasswordFor&for=<?= $email ?>" method="post" id="change-password">
            <div>
                <?= $_SESSION['error'] ?>
            </div>
            <div class="field">
                <label for="password-field">Entrer le nouveau mot de passe : </label>
                <input type="password" name="password" id="password-field" required>
            </div>
            <div class="field">
                <label for="confirmation-password-field">Confirmer votre mot de passe :</label>
                <input type="password" name="confirmation_password" id="confirmation-password-field" required>
            </div>
            <button type="submit">Valider</button>
        </form>
    </section>

    <section id="rights-section">
        <form action = "index.php?action=deleteRights&for=<?= $email ?>" id="rights-view" method= "post">

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
                                        <input type="checkbox" id="checkbox-<?= $type[0] . $tag["id_tag"] ?>" class="<?= "check-right-" . $type?>" name="<?= $type[0] . $tag["id_tag"] ?>">
                                        <label for="checkbox-<?= $type[0] . $tag["id_tag"] ?>"><?= $tag["nom_tag"] ?></label>
                                    </span>
                                    <?php
                                }
                            }
                        }
                        ?>
                        </div>
                        <?php
                    }
                    ?>
                    </div>
                    <?php
                }
            ?>
            <span>
                <button type="button" onclick="openPopup()" title="Ajouter un droit">+ Ajouter</button>
                <button type="submit">Supprimer</button>
            </span>
        </form>

        <!-- Popup pour ajouter un droit -->
        <div id="popup-addright">
            <div>
                <button class='close-button' title='Fermer' onclick ='closePopup()'>←</button>
                <p>Ajouter un droit</p>
            </div>

            <form action = "index.php?action=addRight&for=<?= $email ?>" method= "post">
                <select id="category-selector" name="category" onchange="showTagOptions()" require>
                    <option value="" disabled selected> Choisir un categorie :</option>
                    <?php
                    foreach ($preview_array_category as $categorie)
                    {
                        ?>
                        <option value=<?= $categorie["nom_categorie_tag"] ?>> <?= $categorie["nom_categorie_tag"] ?> </option>
                        <?php
                    }
                    ?>
                </select>

                <select name="tag" require>
                    <option value="" disabled selected> Choisir un tag :</option>
                    <?php
                    foreach ($preview_array_tag as $key=>$categorie)
                    {
                        if (!empty($categorie))
                        {
                            foreach ($categorie as $tag)
                            {
                                ?>
                                <option value=<?= $tag["id_tag"] ?> class="tag-option <?= $key ?>"> <?= $tag["nom_tag"] ?> </option>
                                <?php
                            }
                        }
                    }
                    ?>
                </select>

                <select name="type">
                    <option value="ecriture">Écriture</option>
                    <option value="lecture" selected>lecture</option>
                </select>

                <button type="submit">Valider</button>
            </form>
        </div>

    </section>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>
