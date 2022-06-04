<?php

namespace Application\Controllers\Password;

class RecoverPassword
{
    public function execute()
    {
        $error = "";
        require('public/view/recover_password.php');
    }
}

