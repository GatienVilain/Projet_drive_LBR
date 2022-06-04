<?php

namespace Application\Model;

class User
{
    public function is_connected() : bool
    {
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }

        return !empty($_SESSION['connected']);
    }

    public function logout(): void
    {
        unset($_SESSION['connected']);
        session_destroy();

        header('Location: index.php');
        exit();
    }

}