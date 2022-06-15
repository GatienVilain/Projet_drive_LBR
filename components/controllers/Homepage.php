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
		$tagsFiles = $this->getArrayTagsFilesInstantiate($files);
		$previewTags = $this->previewTagsFilesInstantiate($tagsFiles);

		$extensionsFiles = $this->getArrayExtensionsFilesInstantiate($files);
		$previewExtensions = $this->previewExtensionsFilesInstantiate($extensionsFiles);

		$authorsFiles = $this->getArrayAuthorsFilesInstantiate($files);
		$previewAuthors = $this->previewAuthorsFilesInstantiate($authorsFiles);

		$previewArrayCategory = $this->previewArrayCategory();

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

	private function getArrayTagsFilesInstantiate($filesInstantiate)
	{
		$connection = new DatabaseConnection();

		$arrayTagsFilesInstantiate = array();
		$arrayCategoryTagsFilesInstantiate = array();
		$arrayTags = array();
		foreach($filesInstantiate as $file)
		{
			//var_dump($file->getTags());
			foreach($file->getTags() as $idTag)
			{
				//var_dump($idTags);
				if(!in_array($idTag,$arrayTagsFilesInstantiate))
				{
					array_push($arrayTagsFilesInstantiate,$idTag);
				}
			}

		}
	
		foreach($arrayTagsFilesInstantiate as $idTag)
		{	
			$categoryName = $connection->get_tag_category($idTag)[0]['nom_categorie_tag'];
			
			if(array_key_exists($categoryName,$arrayCategoryTagsFilesInstantiate))
			{
				
				array_push($arrayCategoryTagsFilesInstantiate[$categoryName], array($connection->get_tag($idTag)['nom_tag'],$idTag));
				
			}

			else
			{

				$arrayCategoryTagsFilesInstantiate[$categoryName]=array(array($connection->get_tag($idTag)['nom_tag'],$idTag));
				
			}
			
			
		}

			
		//var_dump($arrayCategoryTagsFilesInstantiate);
		return $arrayCategoryTagsFilesInstantiate;

	}

	private function previewTagsFilesInstantiate($tagsFiles)
	{
		$result="";
		foreach($tagsFiles as $categoryName => $arrayTags){
			//var_dump($arrayTags);
			
			
			$categoryName=$categoryName;
			$result = $result."
				<div class='dropdown'> 
					<div class ='categoryName-line'>
						<button onclick='myFunction(this.id)' class='categoryName-dropdown' title='Afficher tags' id='".$categoryName."-dropdown'>".$categoryName." ⌵</button>";
					
			if(strtolower($categoryName) != "autres")
			{
				$result = $result."<button onclick='' class='delete-categoryName-filter-menu' id='".$categoryName."-dropdown-delete' title='Supprimer catégorie'>×</button>";
			}

			else
			{
				$result = $result."<button onclick='' class='delete-categoryName-filter-menu' id='".$categoryName."-dropdown-delete'>×</button>";
			}
					

			$result = $result."</div><div id='".$categoryName."-dropdown-content' class='dropdown-content'>";

			foreach($arrayTags as $Tags){
				//var_dump($Tags);
				$tagName=$Tags[0];
				$tagId=$Tags[1];
				$result=$result."
					<div class='filter-menu-line-tag'>

                      	<p><input type='checkbox' class ='tagName' id='filterMenu-checkTag".$tagId."' title='Sélectionner un tag'>&emsp;".$tagName."</p>";

				if(strtolower($tagName) != "sans tags")
				{
					$result = $result."<button onclick='' class='delete-tagName-filter-menu' id='filterMenu-deleteTag-".$tagId."' title='Supprimer tag'>×</button>";
				}

				else
				{	
					$result = $result."<button onclick='' class='delete-tagName-filter-menu' id='filterMenu-deleteTag-".$tagId."'>×</button>";
				}
                      	
				$result = $result."</div>";
			}

			$result=$result."</div> </div>";

		}


		return $result;
	}

	private function getArrayExtensionsFilesInstantiate($filesInstantiate)
	{
		$arrayExtensionsFilesInstantiate = array();
		foreach($filesInstantiate as $file)
		{
			$fileExtension = strtolower($file->getFileExtension());
			//var_dump($file->getFileExtension());
			if(!in_array($fileExtension,$arrayExtensionsFilesInstantiate))
			{
				array_push($arrayExtensionsFilesInstantiate,$fileExtension);
			}
		
		}

		//var_dump($arrayExtensionsFilesInstantiate);
	
		return $arrayExtensionsFilesInstantiate;

	}

	
	private function previewExtensionsFilesInstantiate($extensionsFiles)
	{
		$result="";
		foreach($extensionsFiles as $extension){
			//var_dump($arrayTags);
			$result=$result."
			
				<div class='filter-menu-element-extension' id='".$extension."-extension'>

                	<p><input type='checkbox' class ='extentionName' id='".$extension."-filterMenu-checkExtension' title='Sélectionner une extension'>&emsp;".$extension."</p>
                
                </div>";
		}

		return $result;
	}

	private function getArrayAuthorsFilesInstantiate($filesInstantiate)
	{
		$arrayAuthorsFilesInstantiate = array();
		foreach($filesInstantiate as $file)
		{
			$fileAuthor = $file->getAuthorName();
			//var_dump($fileAuthor);
			if(!in_array($fileAuthor,$arrayAuthorsFilesInstantiate))
			{
				array_push($arrayAuthorsFilesInstantiate,$fileAuthor);
			}
		
		}

		//var_dump($arrayAuthorsFilesInstantiate);
	
		return $arrayAuthorsFilesInstantiate;

	}


	private function previewAuthorsFilesInstantiate($authorsFiles)
	{
		$result="";
		foreach($authorsFiles as $author){
			//var_dump($arrayTags);
			$authorId = str_replace(" ","-",$author);
			$result=$result."
			
				<div class='filter-menu-line-author' id='".$authorId."-author'>

                	<p><input type='checkbox' class ='extentionName' id='".$authorId."-filterMenu-checkAuthor' title='Sélectionner une extension'>&emsp;".$author."</p>
                
                </div>";
		}

		return $result;
	}

	private function previewArrayCategory()
	{
		$connection = new DatabaseConnection();
		$allCategory = $connection->get_tag_category();
		//var_dump($allCategory);
		$result = "";
		foreach($allCategory as $key => $arrayCategoryName)
		{
			//var_dump($categoryName['nom_categorie_tag']);
			$categoryName = $arrayCategoryName['nom_categorie_tag'];
			if($categoryName == "autres")
			{
				$result=$result."<option value='".$categoryName."' selected>".$categoryName."</option>";
			}
			
			else
			{
				$result=$result."<option value='".$categoryName."'>".$categoryName."</option>";
			}
			
		}

		return $result;
	}

	



}
