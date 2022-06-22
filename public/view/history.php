<?php $title = "Drive LBR - login"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/history.css\">" ?>
<?php $scripts = "" ?>

<?php require('public/view/banner-menu.php'); ?>

<?php ob_start(); ?>

<!-- Content -->
<article>

    <?php
    foreach ($logs_table as $date=>$day)
    {   ?>

        <section>
            <h4>Historique du <?= $date; ?></h4>
            <table>

                <?php
                foreach ($day as $row)
                {   ?>

                    <tr>
                        <td class="date"><?= $row['date']; ?></td>
                        <td class="hour"><?= $row['hour']; ?></td>
                        <td class="email"><?= $row['email']; ?></td>
                        <td class="message"><?= $row['message']; ?></td>
                    </tr>

                    <?php
                }
                ?>

            </table>
        </section>

        <?php
    }
    ?>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>