<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Model/Files.php");
require_once("components/Tools/CustomSort.php");

use Application\Tools\Database\DatabaseConnection;
use Application\Tools\CustomSort;
use Application\Model\Files;

class Basket
{
	public function execute()
	{
		$sort = new CustomSort();
		$files = $this->instantiate();
		$user = $_SESSION["email"];
		$role = (new DatabaseConnection())->get_user($user)["role"];
		$nbr_files = count($files);
		$error = "";
		if(isset($_SESSION['optionSort']))
		{	
			if($_SESSION['optionSort'] == 'sortAlphabetic')
			{
				if(isset($_SESSION['alphabeticOrder']) && ($_SESSION['alphabeticOrder'] == 'asc' OR $_SESSION['alphabeticOrder'] == 'desc') )
				{
					$files = $sort->sort_by_alphabetical($files, $_SESSION['alphabeticOrder']);
				}
			}

			if($_SESSION['optionSort'] == 'sortModificationDate')
			{
				$files = $sort->sort_by_date($files, $_SESSION['modificationDateOrder']);
			}
			
		}
		
		require('public/view/basket.php');
	}

	private function instantiate()
	{
		$connection = new DatabaseConnection();
		//liste de tous les objets fichiers supprimés auxquelles l'utilisateur peut intéragir avec
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
				$data[] = new Files($tmp[$i],true);
			}
		}

		return $data;
	}
}
