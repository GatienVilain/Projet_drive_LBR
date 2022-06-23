<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Tools/CustomSort.php");
require_once("components/Model/Files/FileCore.php");
require_once("components/Model/Files/FilePreview.php");

use Application\Tools\Database\DatabaseConnection;
use Application\Tools\CustomSort;
use Application\Model\Files\FileCore;
use Application\Model\Files\FilePreview;

class Homepage
{
	public function execute()
	{
		$sort = new CustomSort();
		$user = $_SESSION["email"];
		$role = (new DatabaseConnection())->get_user($user)["role"];
		
		$files = $this->instantiateFileCore();
		
		//Vérifie variable de session existe et est non nulle
		if(!empty($_SESSION['extensionList']))
		{
			$files = $sort->sort_by_extension($files, $_SESSION['extensionList']);
		}

		//Vérifie variable de session existe et est non nulle
		if(!empty($_SESSION['tagIdList']))
		{
			$files = $sort->sort_by_tag($files, $_SESSION['tagIdList']);
		}

		if(!empty($_SESSION['authorList']))
		{
			$files = $sort->sort_by_user($files, $_SESSION['authorList']);
		}

		if($_SESSION['optionSort'] != '')
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
		$Bfiles = $this->instantiateFilePreview($files);
		
		$arrayTagsAddMultipleFiles = $this->getTagsAddMenu();
		$previewAddTagsMultipleFiles = $this->previewTagsAddMultipleFiles($arrayTagsAddMultipleFiles);

		$arrayTagsDeleteMultipleFiles = $this->getTagsDeleteMenu($files);
		$previewDeleteTagsMultipleFiles = $this->previewTagsDeleteMultipleFiles($arrayTagsDeleteMultipleFiles);

		$extensionsFiles = $this->getArrayExtensionsFilesInstantiate($files);
		$previewExtensions = $this->previewExtensionsFilesInstantiate($extensionsFiles);

		$authorsFiles = $this->getArrayAuthorsFilesInstantiate($files);
		$previewAuthors = $this->previewAuthorsFilesInstantiate($authorsFiles);
		
		$error = "";
		$nbr_files = count($Bfiles);
		require('public/view/homepage.php');
	}

	private function getFilesID()
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
			}
		}
		return $tmp;
	}
	
	private function instantiateFileCore()
	{
		$filesID = $this->getFilesID();
		$files = array();
		if (!empty($filesID)) {
			foreach ($filesID as $data) {
				$files[] = new FileCore($data,false);
			}
		}
		return $files;
	}
	
	private function instantiateFilePreview(array $Afiles)
	{
		$files = array();
		if(!empty($Afiles)) {
			$_SESSION['max_homepage'] = (int)(count($Afiles)/12);
			$n = ($_SESSION['homepage']+1)*12;
			if ($n > count ($Afiles)) {$n = count ($Afiles);}
			for ($i = $_SESSION['homepage']*12; $i < $n; $i++) {
				$files[] = new FilePreview($Afiles[$i]);
			}
		}
		return $files;
	}
	
	private function getArrayTagsForAdmin()
	{
		$connection = new DatabaseConnection();
		$allCategory = $connection->get_tag_category();
		$tagsByCategory = array();

		foreach($allCategory as $key => $arrayCategoryName)
		{
			$categoryName = $arrayCategoryName['nom_categorie_tag'];
			$allIdByCategory = $connection->get_tag_by_category($categoryName);
			
			if($allIdByCategory == -1)
			{
				$tagsByCategory[$categoryName]=null;
			}
			else
			{
				foreach($allIdByCategory as $arrayTag)
				{
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
		return $tagsByCategory;
	}

	private function getArrayTagsWithRights($user)
	{
		$connection = new DatabaseConnection();
		$allRights = $connection->get_rights_of_user($user);
		$arrayCategoryTagsWithRights = array();
		$arrayTags = array();

		if($allRights != -1)
		{
			foreach($allRights as $tagWithRights)
			{
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
		}
		return $arrayCategoryTagsWithRights;
	}

	private function previewTagsGuest($tagsWithRights, $previewArrayCategory)
	{
		$connection = new DatabaseConnection();
		$result="";
		foreach($tagsWithRights as $categoryName => $arrayTagsWithRights){
			$result = $result."
				<div class='dropdown'> 
					<div class ='categoryName-line'>
						<button onclick='myFunction(this.id)' class='categoryName-dropdown' title='Afficher tags' id='".$categoryName."-dropdown'>".$categoryName." ⌵</button>
					</div>
					<div id='".$categoryName."-dropdown-content' class='dropdown-content'>";
			
			foreach($arrayTagsWithRights as $tagWithRights)
			{
				foreach($tagWithRights as $tagName => $tagDetails)
				{
					$tagId=$tagDetails['id_tag'];
					$tagWritingRight=$tagDetails['ecriture'];
					$result=$result."
						<div class='filter-menu-line-tag'>

                      		<p class = 'inputCheckboxTag'><input type='checkbox' class ='checkbox-filter-menu-tags' id='filterMenu-checkTag-".$tagId."' title='Sélectionner un tag'>&emsp;".$tagName."</p>";

					if($tagName != "sans tags")
					{	
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

          						<select class='popup-editTag-selectCategory' id='popup-editTag-selectCategory-".$tagId."' name='category'>"
            						.$previewArrayCategory.
          						"</select>
          						<input type='text' class= 'popup-editTag-nameTag' id='popup-editTag-nameTag-".$tagId."' name='tag' value='".$tagName."' placeholder='nouveau nom'>
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
		foreach($tagsWithRights as $categoryName => $arrayTagsWithRights)
		{

			if($arrayTagsWithRights != null)
			{
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

				foreach($arrayTagsWithRights as $key => $tagDetails)
				{
					$tagName=array_keys($tagDetails)[0];
					$tagId=$tagDetails[$tagName];
					$result=$result."
						<div class='filter-menu-line-tag'>

							<p class = 'inputCheckboxTag'><input type='checkbox' class ='checkbox-filter-menu-tags' id='filterMenu-checkTag-".$tagId."' title='Sélectionner un tag'>&emsp;".$tagName."</p>";

					if($tagName != "sans tags")
					{	
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

								<select class='popup-editTag-selectCategory' id='popup-editTag-selectCategory-".$tagId."' name='category'>"
									.$previewArrayCategory.
								"</select>
								<input type='text' class = 'popup-editTag-nameTag' id='popup-editTag-nameTag-".$tagId."' name='tag' value='".$tagName."' placeholder='nouveau nom'>
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

			if(!in_array($fileExtension,$arrayExtensionsFilesInstantiate))
			{
				array_push($arrayExtensionsFilesInstantiate,$fileExtension);
			}
		}
		return $arrayExtensionsFilesInstantiate;
	}

	
	private function previewExtensionsFilesInstantiate($extensionsFiles)
	{
		$result="";
		foreach($extensionsFiles as $extension){
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
			if(!in_array($fileAuthor,$arrayAuthorsFilesInstantiate))
			{
				array_push($arrayAuthorsFilesInstantiate,$fileAuthor);
			}
		
		}
		return $arrayAuthorsFilesInstantiate;
	}

	private function previewAuthorsFilesInstantiate($authorsFiles)
	{
		$result="";
		foreach($authorsFiles as $author){
			$authorId = str_replace(" ","_",$author);
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
		$result = "";
		foreach($allCategory as $key => $arrayCategoryName)
		{
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

	private function getTagsAddMenu()
	{
		$connection = new DatabaseConnection();
		$arrayTagsAddMenu = array();
		$allIdTags = array();	
		$allCategory = $connection->get_tag_category();
		foreach($allCategory as $key => $arrayCategoryName)
		{
			$allIdByCategory = $connection->get_tag_by_category($arrayCategoryName['nom_categorie_tag']);
			if($allIdByCategory != -1)
			{
				foreach($allIdByCategory as $tag)
				{		
					array_push($allIdTags,$tag['id_tag']);
				}
			}
		}
		foreach($allIdTags as $id)
		{		
			$categoryName = $connection->get_tag_category($id)[0]['nom_categorie_tag'];
			if(array_key_exists($categoryName, $arrayTagsAddMenu))
			{
				array_push($arrayTagsAddMenu[$categoryName], array($connection->get_tag($id)['nom_tag']=>$id));
			}			
			else
			{
				$arrayTagsAddMenu[$categoryName]=array(array($connection->get_tag($id)['nom_tag']=>$id));
			}	
		}
		return $arrayTagsAddMenu;
	}

	private function previewTagsAddMultipleFiles($arrayTagsAddMultipleFiles)
	{
		$result = "
        	<div id='add-tags-multipleFiles'>
          		<div class ='addDelete-tags-file-title'>   
				 	<button id='close-button-addTag-multipleFiles' class='close-button-addDeleteTag' title='Fermer' onclick ='closeAddTagsMultipleFiles()'><p>←</p></button>     
            		<p>Ajouter Tag(s)</p>
            	</div>
          		<div class ='addDelete-tags-file-body'>";
		if($arrayTagsAddMultipleFiles != null)
		{
			foreach($arrayTagsAddMultipleFiles as $categoryName => $arrayTags){ 
				$result = $result."
					<div class='dropdown'> 
						<div class ='categoryName-line'>
							<button onclick='myFunctionBis(this.id)' class='categoryName-dropdown' title='Afficher tags' id='".$categoryName."-dropdown-add-tags-multipleFiles'>".$categoryName." ⌵</button>
						</div>
						<div id='".$categoryName."-dropdown-add-tags-multipleFiles-content' class='add-dropdown-content'>";
				foreach($arrayTags as $tags)
				{
					foreach($tags as $tagName => $tagId)
					{
						$result=$result."
							<div class='addDelete-tags-line-tag'>
								<p class = 'inputCheckboxTagAdd'><input type='checkbox' class ='checkbox-add-tags-multipleFiles' id='add-tags-multipleFiles-checkTag-".$tagId."' title='Sélectionner un tag'>&emsp;".$tagName."</p>
							</div>";
					}
				}	
				$result = $result."</div></div>";	
			}
			$result=$result."<div class='container-button-validate-multipleFiles'><button id='add-tag-multiplefile-button-valider' onclick='addTagsMultipleFiles()'>Valider</button></div></div></div>";
		}
		else
		{
			$result = $result."<p>Aucun tag supprimable</div></div>";	
		}
		return $result;
	}
	
	private function getTagsDeleteMenu($files)
	{
		$connection = new DatabaseConnection();
		$arrayTagsDeleteMenu = array();
		$idTagsFile = array();
		$idTagsFiles = array();
		$allIdTags = array();
		foreach($files as $file)
		{
			$idTagsFile = $file->getTags();
			foreach($idTagsFile as $idTag)
			{
				if(!in_array($idTag, $idTagsFiles))
				{
					array_push($idTagsFiles, $idTag);
				}
			}
		}
		$allIdTags = array_values($idTagsFiles);
		if (count($allIdTags) == 1 && $allIdTags[0] == 1)
		{
			$arrayTagsDeleteMenu = null;
			return $arrayTagsDeleteMenu;
		}
		foreach($allIdTags as $id)
		{		
			$categoryName = $connection->get_tag_category($id)[0]['nom_categorie_tag'];
			if(array_key_exists($categoryName, $arrayTagsDeleteMenu))
			{
				array_push($arrayTagsDeleteMenu[$categoryName], array($connection->get_tag($id)['nom_tag']=>$id));
			}			
			else
			{
				$arrayTagsDeleteMenu[$categoryName]=array(array($connection->get_tag($id)['nom_tag']=>$id));
			}	
		}
		return $arrayTagsDeleteMenu;
	}

	private function previewTagsDeleteMultipleFiles($arrayTagsDeleteMultipleFiles)
	{
		$result = "
        	<div id='delete-tags-multipleFiles'>
          		<div class ='addDelete-tags-file-title' id='delete-tags-multipleFiles-title'>   
				 	<button id='close-button-deleteTag-multipleFiles' class='close-button-addDeleteTag' title='Fermer' onclick ='closeDeleteTagsMultipleFiles()'><p>←</p></button>     
            		<p>Supprimer Tag(s)</p>
            	</div>
          		<div class ='addDelete-tags-file-body' id='delete-tags-multipleFiles-body'>";
		
		if($arrayTagsDeleteMultipleFiles != null)
		{
			foreach($arrayTagsDeleteMultipleFiles as $categoryName => $arrayTags){ 
				$result = $result."
					<div class='dropdown'> 
						<div class ='categoryName-line'>
							<button onclick='myFunctionBis(this.id)' class='categoryName-dropdown' title='Afficher tags' id='".$categoryName."-dropdown-delete-tags-multipleFiles'>".$categoryName." ⌵</button>
						</div>
						<div class='delete-dropdown-content' id='".$categoryName."-dropdown-delete-tags-multipleFiles-content'>";
				foreach($arrayTags as $tags)
				{
					foreach($tags as $tagName => $tagId)
					{
						$result=$result."
							<div class='addDelete-tags-line-tag'>
								  <p class = 'inputCheckboxTagDelete'><input type='checkbox' class ='checkbox-delete-tags-multipleFiles' id='delete-tags-multipleFiles-checkTag-".$tagId."' title='Sélectionner un tag'>&emsp;".$tagName."</p>
							</div>";
					}
				}	
				$result = $result."</div></div>";	
			}
			$result=$result."<div class='container-button-validate-multipleFiles'><button id='delete-tag-multipleFiles-button-valider' onclick='deleteTagsMultipleFiles()'>Valider</button></div></div></div>";
		}
		else
		{
			$result = $result."<p>Aucun tag supprimable</div></div>";	
		}
		return $result;
	}

}
