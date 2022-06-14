<?php

namespace Application\Controllers\Rights;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class GetProfile
{
    public function execute()
    {
        
        require('public/view/rights.php');
    }
}