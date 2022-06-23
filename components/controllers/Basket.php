<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Tools/CustomSort.php");
require_once("components/Model/A.php");
require_once("components/Model/B.php");

use Application\Tools\Database\DatabaseConnection;
use Application\Tools\CustomSort;
use Application\Model\A;
use Application\Model\B;

class Basket
{
	public function execute()
	{
		(new DatabaseConnection())->basket_check();
		$role = (new DatabaseConnection())->get_user($_SESSION["email"])["role"];
		$sort = new CustomSort();
		$files = $this->instantiateA();
		
		
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
		$Bfiles = $this->instantiateB($files);
		$nbr_files = count($Bfiles);
		$error = "";
		require('public/view/basket.php');
	}

	private function getFilesID()
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

		if ($result != -1 && !empty($result))
		{
			for($i = 0; $i<count($result); $i++)
			{
				$data[] = $result[$i]["id_fichier"];
			}
		}
		
		return $data;
	}
	
	private function instantiateA()
	{
		$filesID = $this->getFilesID();
		$files = array();
		if (!empty($filesID)) {
			foreach ($filesID as $data) {
				$files[] = new A($data,true);
			}
		}
		return $files;
	}
	
	private function instantiateB(array $Afiles)
	{
		$files = array();
		if(!empty($Afiles)) {
			$_SESSION['max_basketpage'] = (int)(count($Afiles)/12);
			$n = ($_SESSION['basketpage']+1)*12;
			if ($n > count ($Afiles)) {$n = count ($Afiles);}
			for ($i = $_SESSION['basketpage']*12; $i < $n; $i++) {
				$files[] = new B($Afiles[$i]);
			}
		}
		return $files;
	}
}
