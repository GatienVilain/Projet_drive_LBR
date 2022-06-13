<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Tools/CustomSort.php");
require_once("components/Model/Files.php");

use Application\Tools\Database\DatabaseConnection;
use Application\Tools\CustomSort;
use Application\Model\Files;

class Homepage
{
	public function execute()
	{
		$sort = new CustomSort();
		$files = $this->instantiate();
		$files = $sort->sort_by_alphabetical($files,"desc");
		$error = "";
		$role = (new DatabaseConnection())->get_user($_SESSION["email"])["role"];
		$nbr_files = count($files);
		require('public/view/homepage.php');
	}

	private function instantiate()
	{
		$connection = new DatabaseConnection();
		//liste de tous les objets fichiers non supprimés auxquelles l'utilisateur peut intéragir avec 
		$data = array();

		//on vérifie le rôle de l'utilisateur
		if ($connection->get_user($_SESSION['email'])["role"] == 'invite') {
			//si c'est un invité
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
					if ($tmp3 != -1) {
						for ($j = 0; $j < count($tmp3); $j++) {
							$tmp2[] = $tmp3[$j]["id_fichier"];
						}
					}
				}
			}
			$tmp = array_values(array_unique(array_merge($tmp, array_unique($tmp2))));
			if (!empty($tmp)) {
				for ($i = 0; $i < count($tmp); $i++) {
					$data[] = new Files($tmp[$i]);
				}
			}
		}
		else {
			//sinon c'est un admin
			//il voit tous les fichiers non supprimé
			$result = $connection->get_all_files();
			$tmp = array();
			
			if( $result != -1) {
				for ($i = 0; $i < count($result); $i++) {
					$tmp[] = $result[$i]["id_fichier"];
				}
			
				for ($i = 0; $i < count($tmp); $i++) {
					$data[] = new Files($tmp[$i]);
				}
			}
			else {
				$data = $tmp;
			}
		}
		return $data;
	}
}
