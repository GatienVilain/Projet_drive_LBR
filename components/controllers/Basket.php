<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Model/Files.php");

use Application\Tools\Database\DatabaseConnection;
use Application\Model\Files;

class Basket
{
	public function execute()
	{
		$files = $this->instantiate();
		$error = "";
		require('public/view/basket.php');
	}

	private function instantiate()
	{
		$connection = new DatabaseConnection();
		//liste de tous les objets fichiers non supprimés auxquelles l'utilisateur peut intéragir avec
		$data = array();


        if ($connection->get_user($_SESSION["email"])["role"] == 'admin')
		{
            $result = $connection->get_basket_file();
        }
		else {
			$result = $connection->get_basket_file($_SESSION["email"]);
		}

		$tmp = array();
		if ($result != -1)
		{
			for($i = 0; $i<count($result); $i++)
			{
				$tmp[] = $result[$i]["id_fichier"];
			}
		}

		if (!empty($tmp)) {
			for ($i = 0; $i < count($tmp); $i++) {
				$data[] = new Files($tmp[$i]);
			}
		}

		return $data;
	}
}
