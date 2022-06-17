<?php

namespace Application\Controllers\UsersModeration;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class GetUsersModeration
{
	public function execute()
	{
		$users_table = $this->UsersTable();

		$error = "";
		require('public/view/users_moderation.php');
	}

	private function UsersTable(): array
	{
		$user_email = $_SESSION['email'];
		$connection = new DatabaseConnection();

		$all_users = $connection->get_all_users() ;
		sort($all_users);

		// Supprime de la liste l’utilisateur qui demande la page
		$cpt = 0;
		while ( $all_users[$cpt]['email'] != $user_email ) { $cpt++; }
		unset($all_users[$cpt]);

		// Fabrique la liste de tout les utilisateurs
		$i = 0;
		$users_list = array();
		foreach ($all_users as $user)
		{
			$current_user_info = $connection->get_user($user['email']);

			if ( !$current_user_info['compte_supprime'] )
			{
				$users_list[$i] = $current_user_info;
				$users_list[$i++]['email'] = $user['email'];
			}
		}

		return $users_list;
	}
}
