<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class UsersModeration
{
	public function execute()
	{
		$error = "";
		require('public/view/usersmoderation.php');
	}
}
