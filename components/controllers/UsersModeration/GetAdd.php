<?php

namespace Application\Controllers\UsersModeration;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class GetAddPage
{
    public function execute()
	{
		$error = "";
		require('public/view/add_user.php');
	}
}