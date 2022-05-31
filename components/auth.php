<?php 
function est_connecte() : bool {
    
    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }

    return !empty($_SESSION['connecte']);

}



function forcer_utilisateur_connecter(): void {
    if(!est_connecte()){
        header('Location: login.php');
        exit();
    }
    
}

function logout(): void {
    unset($_SESSION['connecte']);
    header('Location: index.php');
    exit();
}

?>