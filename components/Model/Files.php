<?php

namespace Application\Model;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;


class Files
{
    private string $id_fichier;
    private string $auteur;
	private string $source;
    private string $nom_fichier;
	private string $date_publication;
    private string $date_modification;
    private float $taille_Mo;
	private string $type;
    private string $extension;
	private array $tags;
	private bool $ecriture;
	private bool $lecture;

    public function __construct($id_fichier)
    {
        $this->id_fichier = $id_fichier;
		$this->auteur = $this->setAuthor($id_fichier);
		$this->source = $this->setPath($id_fichier);
		$this->nom_fichier = $this->setFilename($id_fichier);
		$this->date_publication = $this->setReleaseDate($id_fichier);
		$this->date_modification = $this->setModificationDate($id_fichier);
		$this->taille_Mo = $this->setFileSize($id_fichier);
		$this->type = $this->setFileType($id_fichier);
		$this->extension = $this->setFileExtension($id_fichier);
		$this->tags = $this->setTags($id_fichier);
		$this->ecriture = $this->setRights($id_fichier)["ecriture"];
		$this->lecture = $this->setRights($id_fichier)["lecture"];
    }
	
	//setteur
	private function setAuthor(int $id_fichier): string
	{
		return (new DatabaseConnection())->get_file($id_fichier)["email"];
	}
	
	private function setPath(int $id_fichier): string
	{
		$connection = new DatabaseConnection();
		return $connection->get_file($id_fichier)["source"] . '\\' . strval($id_fichier);
	}
	
	private function setFilename(int $id_fichier): string
	{
		return (new DatabaseConnection())->get_file($id_fichier)["nom_fichier"];
	}
	
	private function setReleaseDate(int $id_fichier): string
	{
		return (new DatabaseConnection())->get_file($id_fichier)["date_publication"];
	}
	
	private function setModificationDate(int $id_fichier): string
	{
		return (new DatabaseConnection())->get_file($id_fichier)["date_derniere_modification"];
	}
	
	private function setFileSize(int $id_fichier): float
	{
		return (new DatabaseConnection())->get_file($id_fichier)["taille_Mo"];
	}
	
	private function setFileType(int $id_fichier): string
	{
		return (new DatabaseConnection())->get_file($id_fichier)["type"];
	}
	
	private function setFileExtension(int $id_fichier): string
	{
		return (new DatabaseConnection())->get_file($id_fichier)["extension"];
	}
	
	private function setTags(int $id_fichier): array
	{
		$connection = new DatabaseConnection();
		$data = $connection->get_link($id_fichier);
		if ( $data != -1) {
			$array = array();
			for ($i = 0; $i < count($data); $i++) {
				$array[] = $data[$i]["id_tag"];
			}
			return $array;
		}
		return array();
	}
	
	private function setRights(): array
	{
		if ($this->getAuthor() != $_SESSION['email']) {
			$connection = new DatabaseConnection();
			$tags = $this->getTags();
				$ecriture = 0;
				$lecture = 0;
				for ($i = 0; $i < count($tags);$i++) {
					if ($ecriture && $lecture) {
						break;
					}
					
					$rights = $connection->get_rights($_SESSION["email"],$tags[$i]);
					
					if ($rights != -1) {
						$ecriture = $rights["ecriture"];
						$ecriture = $rights["lecture"];
					}
				}
				return array("ecriture" => $ecriture,"lecture" => $lecture);
		}
		else {
			return array("ecriture" => 1,"lecture" => 1);
		}
	}
	
	//getteur
	public function getAuthor(): string
	{
		return $this->auteur;
	}
	
	public function getPath(): string
	{
		return $this->source;
	}
	
	public function getFilename(): string
	{
		return $this->nom_fichier;
	}
	
	public function getReleaseDate(): string
	{
		return $this->date_publication;
	}
	
	public function getModificationDate(): string
	{
		return $this->date_modification;
	}
	
	public function getFileSize(): float
	{
		return $this->taille_Mo;
	}
	
	public function getFileType(): string
	{
		return $this->type;
	}
	
	public function getFileExtension(): string
	{
		return $this->extension;
	}
	
	public function getTags(): array
	{
		return $this->tags;
	}

	public function getTagsNames() 
	{	
		$connection = new DatabaseConnection();
		$arrayTagsNames = array();
		
		$idTags = $this->getTags();
		foreach($idTags as $id)
		{	
			$categoryName = strval($connection->get_tag_category($id)[0]['nom_categorie_tag']);

			if(array_key_exists($categoryName,$arrayTagsNames))
			{
				array_push($arrayTagsNames[$categoryName], strval($connection->get_tag($id)['nom_tag']));
			}

			else{
				$arrayTagsNames[$categoryName]=array(strval($connection->get_tag($id)['nom_tag']));
			}

		}
		return $arrayTagsNames;
	}

	public function previewTags($arrayTagsNames): string
	{
		foreach($arrayTagsNames as $categoryName => $arrayTags){
			$result = "<p class=server-para-categoryName><U>".$categoryName."</u></p>";
			foreach($arrayTags as $Tags){
				$result=$result."<p class=server-para-tag>".$Tags."</p>";
			}

		}


		return $result;
	}
	
	
	public function getWriting(): int
	{
		return $this->ecriture;
	}
	
	public function getReading(): int
	{
		return $this->lecture;
	}

	public function getAuthorName(): string
	{
		$connection = new DatabaseConnection();
		$result = $connection->get_user($this->getAuthor());
		return $result["prenom"].' '.$result["nom"];
	}
	
	public function getAuthorDescription(): string
	{
		$connection = new DatabaseConnection();
		$result = $connection->get_user($this->getAuthor());
		return $result["descriptif"];
	}

	public function preview(): string
	{
		$fileName = $this->getFilename();
		$fileAddedDate=$this->getReleaseDate();
		$fileAuthor=$this->getAuthorName();
		$fileModificationDate=$this->getModificationDate();
		$fileSize=$this->getFileSize();
		$fileTags= $this->getTagsNames();
		$previewTags=$this->previewTags($fileTags);
		$fileExtension=$this->getFileExtension();
		$descriptionAuthor = $this->getAuthorDescription();
		$idFichier=$this->id_fichier;
		$fileType = $this->getFileType();
		$filePath = $this->getPath() . '.' . $fileExtension;
		

		if($fileType == "image")
		{
			$previewFilePath = 'storage\pictures\frames'.DIRECTORY_SEPARATOR.$idFichier.'.'.$fileExtension;
		}

		else if($fileType == "video")
		{
			$previewFilePath = 'storage/videos/frames'.DIRECTORY_SEPARATOR.$idFichier.$fileExtension;
		}
		

		$image = sprintf("<div class=miniature>

			<div class='popup-options' id='%s-popup-options'>

        		<div class='header-popup' id='header-popup-options'>

            		<button id='%s' class='close-button' title='Fermer' onclick ='closePopupOptions(this.id)'><strong>←</strong></button>
           			<p><strong>Options</strong></p>
        
        		</div>

        		<div id='body-popup-options'>

				<a href='%s' download ='%s'><button class='buttonPopupOptions' title='Télécharger le fichier'>Télécharger</button></a>
          			<button class='buttonPopupOptions' title='Supprimer le fichier' onclick='deleteFile($idFichier)'  >Supprimer</button>

        		</div>

    		</div>

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
						
						<p class = 'detail-para'>Auteur:</p>
						<div class = 'server-para-tooltip' id='server-para-nameAuthor'><u>$fileAuthor</u>

							<span class = 'tooltiptext'>$descriptionAuthor</span>

						</div>

					</div>

					<div class='body-popup-detail-line' id='body-popup-detail-line4'>

						<p class = 'detail-para'>Date d'ajout:</p>
						<p class = 'server-para'>$fileAddedDate</p>

					</div>

					<div class='body-popup-detail-line' id='body-popup-detail-line5'>

						<p class = 'detail-para'>Date de modification:</p>
						<p class = 'server-para' id='server-para-modificationDate'>$fileModificationDate</p>

					</div>

					<div class='body-popup-detail-line' id='body-popup-detail-line6'>

						<p class = 'detail-para'>Taille:</p>
						<p class = 'server-para'>$fileSize Mo</p>

					</div>

					<div class='body-popup-detail-line' id='body-popup-detail-line7'>

						<p class = 'detail-para'>Tag:</p>
						<div class = 'server-para' id='server-para-tag'>$previewTags</div>

					</div>

				</div>
	
	
			</div>

			<div class=image> 
				<img id='%s' src='%s' onMouseDown='[openPopup(event, this.id), closePopupUpload()]'/>
			</div> 
			
			<div class = titre> 
				<p> %s </p> 
			</div></div>",$idFichier,$idFichier,$filePath,$this->getFilename(),$idFichier,$idFichier,$idFichier,$previewFilePath,$this->getFilename());
		return $image;
	}
}