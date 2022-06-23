<?php
namespace Application\Model\Files;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class FilePreview
{
	private $data;
	
	public function __construct(FileCore $file)
    {
		$this->data = $file;
	}
	
	//getter
	public function getFile() {
		return $this->data;
	}
	
	//function for preview
	private function getTagsNames() 
	{	
		$connection = new DatabaseConnection();
		$arrayTagsNames = array();
		
		$idTags = $this->getFile()->getTags();
		
		foreach($idTags as $id)
		{	
			$categoryName = $connection->get_tag_category($id)[0]['nom_categorie_tag'];

			if(array_key_exists($categoryName,$arrayTagsNames))
			{
				array_push($arrayTagsNames[$categoryName], $connection->get_tag($id)['nom_tag']);
			}

			else{
				$arrayTagsNames[$categoryName]=array($connection->get_tag($id)['nom_tag']);
			}

		}
		return $arrayTagsNames;
	}
	
	private function getTagsDeleteMenu()
	{
		$connection = new DatabaseConnection();
		$arrayTagsDeleteMenu = array();
		$idTagsFile = $this->getFile()->getTags();	
		$role = $connection->get_user($_SESSION["email"])["role"];
		$idTagsAllowed = array();
		$idTagsNotAllowed=array();
		if($role == 'invite')
		{
			$idTagsWithRights = $connection->get_rights_of_user($_SESSION["email"]);
			if($idTagsWithRights != -1)
			{
				foreach($idTagsWithRights as $key => $arrayTagRights)
				{
					if($arrayTagRights['ecriture'] == 0)
					{
						array_push($idTagsNotAllowed,$arrayTagRights['id_tag']);
					}
				}
				$idTagsAllowed=array_diff($idTagsFile, $idTagsNotAllowed);
			}
			else
			{
				$arrayTagsDeleteMenu = null;
				return $arrayTagsDeleteMenu;
			}
		}
		else if($role == 'admin')
		{
			$idTagsAllowed = $idTagsFile;
		}
		if(count($idTagsAllowed) == 1 && $idTagsAllowed[0] == 1)
		{
			$arrayTagsDeleteMenu = null;
			return $arrayTagsDeleteMenu;
		}
		foreach($idTagsAllowed as $id)
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
	
	private function getTagsAddMenu()
	{
		$connection = new DatabaseConnection();
		$arrayTagsAddMenu = array();
		$idTagsFile = $this->getFile()->getTags();	
		$role = $connection->get_user($_SESSION["email"])["role"];
		$idTagsAllowed = array();
		$idTagsNotAllowed=array();
		if($role == 'invite')
		{
			$idTagsWithRights = $connection->get_rights_of_user($_SESSION["email"]);
			if($idTagsWithRights != -1)
			{
				foreach($idTagsWithRights as $key => $arrayTagRights)
				{
					if($arrayTagRights['ecriture'] == 1)
					{
						array_push($idTagsAllowed,$arrayTagRights['id_tag']);
					}
				}
				$idTagsAllowed=array_diff($idTagsAllowed, $idTagsFile);
				if(empty($idTagsAllowed))
				{
					$arrayTagsAddMenu = null;
					return $arrayTagsAddMenu;
				}
			}
			else
			{
				$arrayTagsAddMenu = null;
				return $arrayTagsAddMenu;
			}
		}

		else if($role == 'admin')
		{
			$idTagsAllowed = array();
			$allCategory = $connection->get_tag_category();
			foreach($allCategory as $key => $arrayCategoryName)
			{
				$allIdByCategory = $connection->get_tag_by_category($arrayCategoryName['nom_categorie_tag']);
				if($allIdByCategory != -1)
				{
					foreach($allIdByCategory as $tag)
					{		
						array_push($idTagsAllowed,$tag['id_tag']);
					}
				}
			}
			$idTagsAllowed = array_diff($idTagsAllowed, $idTagsFile);
		}
		foreach($idTagsAllowed as $id)
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
	
	private function previewTagsAddMenu($arrayTagsAddMenu)
	{
		$idFichier=$this->getFile()->getFileID();
		$result = "
        	<div class='add-tags' id='add-tags-file-".$idFichier."'>
          		<div class ='addDelete-tags-file-title'>   
				 	<button id='close-button-addTag-".$idFichier."' class='close-button-addDeleteTag' title='Fermer' onclick ='closePopupAddTag(this.id)'><p>←</p></button>     
            		<p>Ajouter Tag(s)</p>
            	</div>
          		<div class ='addDelete-tags-file-body'>";
		if($arrayTagsAddMenu != null)
		{	
			foreach($arrayTagsAddMenu as $categoryName => $arrayTags){ 
				$result = $result."
					<div class='dropdown'> 
						<div class ='categoryName-line'>
							<button onclick='myFunctionBis(this.id)' class='categoryName-dropdown' title='Afficher tags' id='".$categoryName."-dropdown-addDelete-tags'>".$categoryName." ⌵</button>
						</div>
						<div id='".$categoryName."-dropdown-addDelete-tags-content' class='add-dropdown-content'>";
				foreach($arrayTags as $tags)
				{
					foreach($tags as $tagName => $tagId)
					{
						$result=$result."
							<div class='addDelete-tags-line-tag'>
								<p class = 'inputCheckboxTagAdd'><input type='checkbox' class ='checkbox-add-tags' id='add-tags-checkTag-".$tagId."' title='Sélectionner un tag'>&emsp;".$tagName."</p>
							</div>";
					}
				}	
				$result = $result."</div></div>";	
			}
			$result=$result."<button id='add-tag-file-button-valider' onclick='addTagsFile(this.id)'>Valider</button></div></div>";
		}
		else
		{
			$result = $result."<p>Aucun tag ajoutable</div></div>";	
		}
		
		return $result;
	}

	private function previewTagsDeleteMenu($arrayTagsDeleteMenu)
	{
		$idFichier=$this->getFile()->getFileID();
		$result = "
        	<div class='delete-tags' id='delete-tags-file-".$idFichier."'>
          		<div class ='addDelete-tags-file-title' id='delete-tags-file-title'>   
				 	<button id='close-button-deleteTag-".$idFichier."' class='close-button-addDeleteTag' title='Fermer' onclick ='closePopupDeleteTag(this.id)'><p>←</p></button>     
            		<p>Supprimer Tag(s)</p>
            	</div>
          		<div class ='addDelete-tags-file-body' id='delete-tags-file-body'>";
		
		if($arrayTagsDeleteMenu != null)
		{
			foreach($arrayTagsDeleteMenu as $categoryName => $arrayTags){ 
				$result = $result."
					<div class='dropdown'> 
						<div class ='categoryName-line'>
							<button onclick='myFunction(this.id)' class='categoryName-dropdown' title='Afficher tags' id='".$categoryName."-dropdown-addDelete-tags'>".$categoryName." ⌵</button>
						</div>
						<div class='delete-dropdown-content' id='".$categoryName."-dropdown-addDelete-tags-content'>";
				foreach($arrayTags as $tags)
				{
					foreach($tags as $tagName => $tagId)
					{
						$result=$result."
							<div class='addDelete-tags-line-tag'>
								  <p class = 'inputCheckboxTag'><input type='checkbox' class ='checkbox-delete-tags' id='delete-tags-checkTag-".$tagId."' title='Sélectionner un tag'>&emsp;".$tagName."</p>
							</div>";
					}
				}	
				$result = $result."</div></div>";	
			}
			$result=$result."<button id='delete-tag-file-button-valider' onclick='deleteTagsFile(this.id)'>Valider</button></div></div>";
		}

		else
		{
			$result = $result."<p>Aucun tag supprimable</div></div>";	
		}
		
			
		return $result;
	}
	
	private function previewTags($arrayTagsNames): string
	{
		$result="";
		foreach($arrayTagsNames as $categoryName => $arrayTags){
			$result = $result."<p class=server-para-categoryName><U>".$categoryName."</u></p>";
			foreach($arrayTags as $Tags){
				$result=$result."<p class=server-para-tag>".$Tags."</p>";
			}
		}
		return $result;
	}
	
	public function preview(): string
	{
		$fileAuthor=$this->getFile()->getAuthorName();

		$fileAddedDate = $this->getFile()->getReleaseDate();
		$fileModificationDate = $this->getFile()->getModificationDate();
		$fileAddedDate = date("d-m-Y",strtotime($fileAddedDate)); 
		$fileModificationDate = date("d-m-Y",strtotime($fileModificationDate)); 
		//$previewAddTagsMenu = $this->previewTagsAddMenu($this->getTagsAddMenu());
		//$previewDeleteTagsMenu = $this->previewTagsDeleteMenu($this->getTagsDeleteMenu());



		$fileSize = $this->getFile()->getFileSize();
		$fileTags = $this->getTagsNames();
		$previewTags = $this->previewTags($fileTags);
		$fileExtension = $this->getFile()->getFileExtension();
		$descriptionAuthor = $this->getFile()->getAuthorDescription();
		$idFichier=$this->getFile()->getFileID();
		$duration = $this->getFile()->getFileDuration();
		$fileName = $this->getFile()->getFilename();
		$fileType = $this->getFile()->getFileType();
		$deleted = $this->getFile()->getDeleted();
		$filePath = $this->getFile()->getPath() . '.' . $fileExtension;
		

		if($fileType == "image")
		{
			$previewFilePath = 'storage\pictures\frames'.DIRECTORY_SEPARATOR.$idFichier.'.'.$fileExtension;

			if(!is_file($previewFilePath))
			{
			$previewFilePath = 'storage\pictures\frames\error.png';
			}
			
		}

		else if($fileType == "video")
		{
			$previewFilePath = 'storage\videos\frames'.DIRECTORY_SEPARATOR.$idFichier.$fileExtension;

			if(!is_file($previewFilePath))
			{
			$previewFilePath = 'storage\videos\frames\error.png';
			}
		}

		$videoDuration = '';

		if (!empty($duration)) {
			$videoDuration = sprintf("
			<div class='body-popup-detail-line' id='body-popup-detail-line8'>

				<p class = 'detail-para'>Duree:</p>
				<p class = 'server-para'>$duration</p>

			</div>
			",);
		}
		$state = 'modification';
		if ($deleted) {
			$state = 'suppression';
			$popupOptions = sprintf("
				<div class='popup-options' id='%s-popup-options'>

					<div class='header-popup' id='header-popup-options'>

						<button id='%s' class='close-button' title='Fermer' onclick ='closePopupOptions(this.id)'><strong>←</strong></button>
						<p><strong>Options</strong></p>
			
					</div>

					<div id='body-popup-options'>

						<button class='buttonPopupOptions' title='Restaurer le fichier' onclick='recoverFile($idFichier)'>Restaurer</button>
						<button class='buttonPopupOptions' title='Supprimer définitivement le fichier' onclick='deleteFile($idFichier)'>Supprimer</button>

					</div>

				</div>",$idFichier,$idFichier);
		}
		else {
			$popupOptions = sprintf("
				<div class='popup-options' id='%s-popup-options'>

					<div class='header-popup' id='header-popup-options'>

						<button id='%s' class='close-button' title='Fermer' onclick ='closePopupOptions(this.id)'><strong>←</strong></button>
						<p><strong>Options</strong></p>
			
					</div>

					<div id='body-popup-options'>

					<a href='%s' download ='%s'><button class='buttonPopupOptions' title='Télécharger le fichier'>Télécharger</button></a>
						<button class='buttonPopupOptions' title='Supprimer le fichier en le mettant dans la corbeille' onclick='basketFile($idFichier)'  >Supprimer</button>

					</div>

				</div>",$idFichier,$idFichier,$filePath,$fileName);
		}
		
		$popupDetails = sprintf("
			<div class = 'popup-detail' id='%s-popup-detail'>

				<div class='header-popup' id='header-popup-detail'>

					<button id='%s' class='close-button' title='Fermer' onclick ='closePopupDetail(this.id)'><strong>←</strong></button>
					<p><strong>Informations fichier</strong></p>

				</div>

				<div id='body-popup-detail'>

					<div class='body-popup-detail-line' id='body-popup-detail-line1'>

						<p class = 'detail-para'>Nom:</p>
						<p class = 'server-para'>$fileName</p>

					</div>

					<div class='body-popup-detail-line' id='body-popup-detail-line2'>

						<p class = 'detail-para'>Type:</p>
						<p class = 'server-para'>$fileType</p>

					</div>

					<div class='body-popup-detail-line' id='body-popup-detail-line3'>

						<p class = 'detail-para'>Extension:</p>
						<p class = 'server-para'>$fileExtension</p>

					</div>

					<div class='body-popup-detail-line' id='body-popup-detail-line4'>
						
						<p class = 'detail-para'>Auteur:</p>
						<div class = 'server-para-tooltip' id='server-para-nameAuthor'><u>$fileAuthor</u>

							<span class = 'tooltiptext'><p>$descriptionAuthor</p></span>

						</div>

					</div>

					<div class='body-popup-detail-line' id='body-popup-detail-line5'>

						<p class = 'detail-para'>Date d'ajout:</p>
						<p class = 'server-para'>$fileAddedDate</p>

					</div>

					<div class='body-popup-detail-line' id='body-popup-detail-line6'>

						<p class = 'detail-para'>Date de %s:</p>
						<p class = 'server-para' id='server-para-modificationDate'>$fileModificationDate</p>

					</div>

					<div class='body-popup-detail-line' id='body-popup-detail-line7'>

						<p class = 'detail-para'>Taille:</p>
						<p class = 'server-para'>$fileSize Mo</p>

					</div>
					
					%s

					<div class='body-popup-detail-line' id='body-popup-detail-line9'>
						<div id='popup-detail-line-tags'>
							<p class = 'detail-para'>Tag(s):</p>
						</div>
						
						<div class = 'server-para' id='server-para-tag'>$previewTags</div></div>

					</div> 
			</div>",$idFichier,$idFichier,$state,$videoDuration);

		if ($this->getFile()->getWriting())
		{
			$write_textarea = '';
		}
		else {
			$write_textarea = 'disabled';
		}

		if ($fileType == "image")
		{
			$image = sprintf("
				<div oncontextmenu='return false;' class=image> 
					<img class=popup id='%s' src=%s>
				</div> 
				
				<div class = titre> 
					<input type='checkbox' class ='checkbox-file' id='checkFile-".$idFichier."' title='Sélectionner un fichier'>
					<input type='text' class='title-file' name='".$idFichier."' placeholder='%s' value='%s' ".$write_textarea." required></input>
					<button class ='button-information' id='button-information-".$idFichier."' title ='Informations' onclick='openPopupDetailMobile(this.id)'>ℹ</button> 
				</div></div>",$idFichier,$previewFilePath,$fileName,$fileName);
				
			return "<div class= miniature>" . $popupOptions . $popupDetails . $image;
		}
		elseif ($fileType == "video") {
			$miniature = '';
			if(!in_array($fileExtension, array("mp4","webm","ogg"))) {
				$miniature = "poster='storage/pictures/frames/error.png'";
			}
			$video = sprintf("
				<div oncontextmenu='return false;' class=video> 
					<video class=popup id='%s' %s>
						<source src=%s type='video/%s'>
						Your browser does not support the video tag.
					</video>
				</div> 
				
				<div class = titre> 
					<input type='checkbox' class ='checkbox-file' id='checkFile-".$idFichier."' title='Sélectionner un fichier'>
					<input type='text' class='title-file' name='".$idFichier."' placeholder='%s' value='%s' ".$write_textarea." required></input>
					<button class ='button-information' id='button-information-".$idFichier."' title ='Informations' onclick='openPopupDetailMobile(this.id)'>ℹ</button> 
				</div></div>",$idFichier,$miniature,$filePath,$fileExtension,$fileName,$fileName);
				
			return "<div class= miniature>" . $popupOptions . $popupDetails . $video;
		}
		
		return -1;
	}
}