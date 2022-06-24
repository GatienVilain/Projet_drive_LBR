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
	
	//On récupère les tags associés au fichier et on récupère leur nom
	private function getTagsNames() 
	{	
		$connection = new DatabaseConnection();
		$arrayTagsNames = array();
		//On obtient tous les tags associés au fichier
		$idTags = $this->getFile()->getTags();
		foreach($idTags as $id)
		{	
			//On associe l'id tag avec son nom
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
	
	//Fonction permettant de générer le preview des tags dans la popup informations
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
	
	//Fonction gérant le visuel du fichier (avec la popup informations associée et la miniature)
	public function preview(): string
	{
		$fileAuthor=$this->getFile()->getAuthorName();
		$fileAddedDate = $this->getFile()->getReleaseDate();
		$fileAddedDate = date("d-m-Y",strtotime($fileAddedDate)); 
		$fileModificationDate = $this->getFile()->getModificationDate();
		$fileModificationDate = date("d-m-Y",strtotime($fileModificationDate)); 
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
		//Popup informations
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
						<p class = 'detail-para'>Tag(s):</p>	
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
				
			return "<div class= miniature>" . $popupDetails . $image;
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
				
			return "<div class= miniature>" . $popupDetails . $video;
		}
		return -1;
	}
}