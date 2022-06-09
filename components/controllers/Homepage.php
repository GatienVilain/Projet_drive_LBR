<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Model/Files.php");

use Application\Tools\Database\DatabaseConnection;
use Application\Model\Files;

class Homepage
{
	public function execute()
	{
		$files = $this->instantiate();
		$error = "";
		require('public/view/homepage.php');
	}

	private function instantiate()
	{
		$connection = new DatabaseConnection();
		//liste de tous les objets fichiers non supprimés auxquelles l'utilisateur peut intéragir avec 
		$data = array();

		//on ajoute les fichiers propre à l'utilisateur
		$tmp = array(); //liste des id_fichiers à instantier
		$result = $connection->get_files_of_user($_SESSION['email']);

		if ($result != -1) {
			for ($i = 0; $i < count($result); $i++) {
				$tmp[] = $result[$i]["id_fichier"];
			}
		}

		//on ajoute les fichiers auxquelles il a des droits dessus et qui ne lui appartienne pas
		$tags = array();
		$tmp2 = array();
		$rights = $connection->get_rights_of_user($_SESSION['email']);

		if ($rights != -1) {
			for ($i = 0; $i < count($rights); $i++) {
				$tags[] = $rights[$i]["id_tag"];
			}

			for ($i = 0; $i < count($tags); $i++) {
				$tmp3 = $connection->get_files_by_link($tags[$i]);
				for ($j = 0; $j < count($tmp3); $j++) {
					$tmp2[] = $tmp3[$j]["id_fichier"];
				}
			}
		}
		$tmp = array_unique(array_merge($tmp, array_unique($tmp2)));

		if (!empty($tmp)) {
			for ($i = 0; $i < count($tmp); $i++) {
				$data[] = new Files($tmp[$i]);
			}
		}
		

		return $data;
	}
}
