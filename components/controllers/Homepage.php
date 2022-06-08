<?php

namespace Application\Controllers;

class Homepage
{
    public function execute()
    {
        $error = "";
        require('public/view/homepage.php');
    }
}
