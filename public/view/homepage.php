<?php $title = "Drive LBR - login"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/login.css\">" ?>
<?php $scripts = "" ?>

<?php require('public/view/banner-menu.php'); ?>

<?php ob_start(); ?>

<!-- Content -->

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>