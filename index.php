<?php // Routeur

require_once("components/controllers/login.php");

try {

    login();

} catch (Exception $e) {

    echo 'erreur router::index.php';
}