<?php

class utilisateur
{

    public function est_connecte() : bool
    {
        if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }

        return !empty($_SESSION['connecte']);
    }

    public function forcer_utilisateur_connecter(): void
    {
        if ( ! $this->est_connecte() ){
            header('Location: login.php');
            exit();
        }
    }

    public function logout(): void
    {
        unset($_SESSION['connecte']);
        header('Location: index.php');
        exit();
    }

}