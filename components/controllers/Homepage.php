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
		$user = $_SESSION["email"];
		$role = (new DatabaseConnection())->get_user($user)["role"];

		//Vérifie variable de session existe et est non nulle
		if(isset($_SESSION['tagIdList']) && ($_SESSION['tagIdList'] != null))
		{
  			//var_dump($_SESSION['tagIdList']);
  
		}


		//Vérifie variable de session existe et est non nulle
		if(isset($_SESSION['extensionList']) && ($_SESSION['extensionList'] != null))
		{
  			//var_dump($_SESSION['extensionList']);
  
		}

		$previewArrayCategory = $this->previewArrayCategory();

		if($role == 'invite')
		{
			$arrayTagsWithRights = $this->getArrayTagsWithRights($user);
			$previewTags = $this->previewTagsGuest($arrayTagsWithRights, $previewArrayCategory);
		}

		else if($role == 'admin')
		{
			$arrayAllTags = $this->getArrayTagsForAdmin();
			$previewTags = $this->previewTagsAdmin($arrayAllTags, $previewArrayCategory);

		}
		
		
		$extensionsFiles = $this->getArrayExtensionsFilesInstantiate($files);
		$previewExtensions = $this->previewExtensionsFilesInstantiate($extensionsFiles);

		$authorsFiles = $this->getArrayAuthorsFilesInstantiate($files);
		$previewAuthors = $this->previewAuthorsFilesInstantiate($authorsFiles);

		
		$error = "";
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

	private function getArrayTagsForAdmin()
	{
		$connection = new DatabaseConnection();
		$allCategory = $connection->get_tag_category();
		$tagsByCategory = array();
		//var_dump($allCategory);

		foreach($allCategory as $key => $arrayCategoryName)
		{

			//var_dump($arrayCategoryName['nom_categorie_tag']);
			$categoryName = $arrayCategoryName['nom_categorie_tag'];
			$allIdByCategory = $connection->get_tag_by_category($categoryName);
			//var_dump($connection->get_tag_by_category($categoryName));
			
			if($allIdByCategory == -1)
			{
				$tagsByCategory[$categoryName]=null;
			}


			else
			{

				//var_dump($allIdByCategory);
				foreach($allIdByCategory as $arrayTag)
				{
					//var_dump($idTag);
					if(array_key_exists($categoryName, $tagsByCategory))
					{
						array_push($tagsByCategory[$categoryName], array($connection->get_tag($arrayTag['id_tag'])['nom_tag']=>$arrayTag['id_tag']));
					}
					
					else
					{
						$tagsByCategory[$categoryName]=array(array($connection->get_tag($arrayTag['id_tag'])['nom_tag']=>$arrayTag['id_tag']));
					}	
					
				}
		
			}
			
		}

		//var_dump($tagsByCategory);
		return $tagsByCategory;

	}

	private function getArrayTagsWithRights($user)
	{
		$connection = new DatabaseConnection();
		$allRights = $connection->get_rights_of_user($user);
		//var_dump($allRights);
		$arrayCategoryTagsWithRights = array();
		$arrayTags = array();
		foreach($allRights as $tagWithRights)
		{
			//var_dump($tagWithRights);
			$categoryName = $connection->get_tag_category($tagWithRights['id_tag'])[0]['nom_categorie_tag'];
			
			if(array_key_exists($categoryName,$arrayCategoryTagsWithRights))
			{
				
				array_push($arrayCategoryTagsWithRights[$categoryName], array($connection->get_tag($tagWithRights['id_tag'])['nom_tag']=>$tagWithRights));
				
			}

			else
			{

				$arrayCategoryTagsWithRights[$categoryName]=array(array($connection->get_tag($tagWithRights['id_tag'])['nom_tag']=>$tagWithRights));
				
			}
		}

		//var_dump($arrayCategoryTagsWithRights);
		return $arrayCategoryTagsWithRights;

	}

	private function previewTagsGuest($tagsWithRights, $previewArrayCategory)
	{
		$connection = new DatabaseConnection();
		$result="";
		foreach($tagsWithRights as $categoryName => $arrayTagsWithRights){
			//var_dump($categoryName);
			
			$result = $result."
				<div class='dropdown'> 
					<div class ='categoryName-line'>
						<button onclick='myFunction(this.id)' class='categoryName-dropdown' title='Afficher tags' id='".$categoryName."-dropdown'>".$categoryName." ⌵</button>";
					
			if($categoryName != "autres")
			{
				$result = $result."
					<button onclick='openEditCategory(this.id)' class='edit-categoryName-filter-menu' id='".$categoryName."-edit-categoryName' title='Modifier nom catégorie'>🖉</button>
					<button onclick='deleteCategory(this.id)' class='delete-categoryName-filter-menu' id='".$categoryName."-dropdown-delete' title='Supprimer catégorie'>×</button>";
			
				$result= $result."

					<div class='popup-editCategory' id='popup-editCategory-".$categoryName."'>

						<div class='header-popup-editTagCategory' id='header-popup-editCategory'>
								<button id='close-button-editCategory-".$categoryName."' class='close-button-editTagCategory' title='Fermer' onclick ='closeEditCategory(this.id)'><p>←</p></button>
								<p>Modifier catégorie</p>
						</div>

						<div id='body-popup-editCategory'>

								<input type='text' id='popup-editCategory-nameCategory' name='category' value='".$categoryName."'placeholder='nouveau nom'>
								<button class='button-valider-editCategory' id='editCategory-button-validate-".$categoryName."' onclick='editCategory(this.id)'>Valider</button>
			
						</div>

					</div>";
			
			
			}
					
			

			$result = $result."</div><div id='".$categoryName."-dropdown-content' class='dropdown-content'>";

			foreach($arrayTagsWithRights as $tagWithRights)
			{
				//var_dump($Tags);
				foreach($tagWithRights as $tagName => $tagDetails)
				{
					//var_dump($tagDetails);
					$tagId=$tagDetails['id_tag'];
					$tagWritingRight=$tagDetails['ecriture'];
					$result=$result."
						<div class='filter-menu-line-tag'>

                      		<p class = 'inputCheckboxTag'><input type='checkbox' class ='checkbox-filter-menu-tags' id='filterMenu-checkTag-".$tagId."' title='Sélectionner un tag'>&emsp;".$tagName."</p>";

					if($tagName != "sans tags")
					{	
						//var_dump($user);
						//var_dump($tagId);
						//var_dump($connection->get_rights($user, $tagId));

						//var_dump($tagId);
						//var_dump($connection->get_rights($user, $tagId)['lecture']);
						if($tagWritingRight == 1)
						{
					
							$result = $result."
							<button onclick='openEditTag(this.id)' class='edit-tagName-filter-menu' id='edit-tagName-".$tagId."' title='Modifier nom tag'>🖉</button>
							<button onclick='deleteTag(this.id)' class='delete-tagName-filter-menu' id='filterMenu-deleteTag-".$tagId."' title='Supprimer tag'>×</button>";

						}
						
					}
                      	
					$result = $result."												
						<div class='popup-editTag' id='popup-editTag-".$tagId."'>

        					<div class='header-popup-editTagCategory' id='header-popup-editTag'>
								<button id='close-button-editTag-".$tagId."' class='close-button-editTagCategory' title='Fermer' onclick ='closeEditTag(this.id)'><p>←</p></button>
          						<p>Modifer tag</p>
        					</div>

        					<div id='body-popup-editTag'>

          						<select id='popup-editTag-selectCategory' name='category'>"
            						.$previewArrayCategory.
          						"</select>
          						<input type='text' id='popup-editTag-nameTag' name='tag' value='".$tagName."' placeholder='nouveau nom'>
            					<button class='button-valider'  id='editTag-button-validate-".$tagId."' onclick='editTag(this.id)'>Valider</button>
          					
        					</div>

      					</div>

	  				</div>";

				}
				

			}

			$result=$result."</div> </div>";

		}


		return $result;
	}

	private function previewTagsAdmin($tagsWithRights, $previewArrayCategory)
	{
		$connection = new DatabaseConnection();
		$result="";
		//var_dump($tagsWithRights);
		//var_dump($_SESSION['tagsIdList']);

		foreach($tagsWithRights as $categoryName => $arrayTagsWithRights)
		{

			if($arrayTagsWithRights != null)
			{
				//var_dump($arrayTagsWithRights);
				//var_dump($tagDetails);
				$result = $result."
					<div class='dropdown'> 
						<div class ='categoryName-line'>
							<button onclick='myFunction(this.id)' class='categoryName-dropdown' title='Afficher tags' id='".$categoryName."-dropdown'>".$categoryName." ⌵</button>";
						
				if($categoryName != "autres")
				{
					$result = $result."
						<button onclick='openEditCategory(this.id)' class='edit-categoryName-filter-menu' id='".$categoryName."-edit-categoryName' title='Modifier nom catégorie'>🖉</button>
						<button onclick='deleteCategory(this.id)' class='delete-categoryName-filter-menu' id='".$categoryName."-dropdown-delete' title='Supprimer catégorie'>×</button>";
				
					$result= $result."

						<div class='popup-editCategory' id='popup-editCategory-".$categoryName."'>

							<div class='header-popup-editTagCategory' id='header-popup-editCategory'>
									<button id='close-button-editCategory-".$categoryName."' class='close-button-editTagCategory' title='Fermer' onclick ='closeEditCategory(this.id)'><p>←</p></button>
									<p>Modifier catégorie</p>
							</div>

							<div id='body-popup-editCategory'>

									<input type='text' id='popup-editCategory-nameCategory' name='category' value='".$categoryName."'placeholder='nouveau nom'>
									<button class='button-valider-editCategory' id='editCategory-button-validate".$categoryName."' onclick='editCategory(this.id)'>Valider</button>
			
							</div>

						</div>";


				}

				
						

				$result = $result."</div><div id='".$categoryName."-dropdown-content' class='dropdown-content'>";


				//var_dump($arrayTagsWithRights);
				foreach($arrayTagsWithRights as $key => $tagDetails)
				{

					//var_dump($tagDetails);
					$tagName=array_keys($tagDetails)[0];
					$tagId=$tagDetails[$tagName];
					$result=$result."
						<div class='filter-menu-line-tag'>

							<p class = 'inputCheckboxTag'><input type='checkbox' class ='checkbox-filter-menu-tags' id='filterMenu-checkTag-".$tagId."' title='Sélectionner un tag'>&emsp;".$tagName."</p>";

					if($tagName != "sans tags")
					{	
						//var_dump($user);
						//var_dump($tagId);
						//var_dump($connection->get_rights($user, $tagId));

						//var_dump($tagId);
						//var_dump($connection->get_rights($user, $tagId)['lecture']);

						$result = $result."
						<button onclick='openEditTag(this.id)' class='edit-tagName-filter-menu' id='edit-tagName-".$tagId."' title='Modifier nom tag'>🖉</button>
						<button onclick='deleteTag(this.id)' class='delete-tagName-filter-menu' id='filterMenu-deleteTag-".$tagId."' title='Supprimer tag'>×</button>";

					}

					
							
					$result = $result."												
						<div class='popup-editTag' id='popup-editTag-".$tagId."'>

							<div class='header-popup-editTagCategory' id='header-popup-editTag'>
								<button id='close-button-editTag-".$tagId."' class='close-button-editTagCategory' title='Fermer' onclick ='closeEditTag(this.id)'><p>←</p></button>
								<p>Modifer tag</p>
							</div>

							<div id='body-popup-editTag'>

								<select id='popup-editTag-selectCategory' name='category'>"
									.$previewArrayCategory.
								"</select>
								<input type='text' id='popup-editTag-nameTag' name='tag' value='".$tagName."' placeholder='nouveau nom'>
								<button class='button-valider'  id='editTag-button-validate-".$tagId."' onclick='editTag(this.id)'>Valider</button>
								
							</div>

						</div>

					</div>";					

				}

				$result=$result."</div> </div>";
				
			}				

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

                	<p><input type='checkbox' class ='checkbox-filter-menu-extensions' id='".$extension."-filterMenu-checkExtension' title='Sélectionner une extension'>&emsp;".$extension."</p>
                
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

                	<p><input type='checkbox' class ='checkbox-filter-menu-authors' id='".$authorId."-filterMenu-checkAuthor' title='Sélectionner une extension'>&emsp;".$author."</p>
                
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
