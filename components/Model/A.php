<?php
namespace Application\Model;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class A
{
	private string $id_fichier;
    private string $auteur;
	private string $nom_auteur;
	private string $source;
    private string $nom_fichier;
	private string $date_publication;
    private string $date_modification;
    private float $taille_Mo;
	private string $duree;
	private string $type;
    private string $extension;
	private array $tags;
	private bool $ecriture;
	private bool $lecture;
	private bool $deleted;
	
	public function __construct($id_fichier,$deleted)
    {
		$connection = new DatabaseConnection();
		$result = $connection->get_file($id_fichier);
        $this->id_fichier = $id_fichier;
		$this->deleted = $deleted;
		$this->auteur = $result["email"];
		$this->nom_auteur = $this->setAuthorName($this->getAuthor());
		$this->source = $result["source"] . '\\' . strval($id_fichier);
		$this->nom_fichier = $result["nom_fichier"];
		$this->date_publication = $result["date_publication"];
		$this->date_modification = $result["date_derniere_modification"];
		$this->taille_Mo = $result["taille_Mo"];
		$this->duree = ($result["duree"] == null) ? '' : $result["duree"];
		$this->type = $result["type"];
		$this->extension = $result["extension"];
		$this->tags = $this->setTags($id_fichier);
		$tmp = $this->setRights($id_fichier);
		$this->ecriture = $tmp['ecriture'];
		$this->lecture = $tmp['lecture'];
    }

	//setter
	private function setAuthorName($email): string
	{
		$connection = new DatabaseConnection();
		$result = $connection->get_user($email);
		return $result["prenom"].' '.$result["nom"];
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
		$connection = new DatabaseConnection();

		if ($connection->get_user($_SESSION['email'])["role"] == "invite" && $this->getAuthor() != $_SESSION['email']) 
		{
			$userRights = $connection->get_rights_of_user($_SESSION['email']);
			$fileTags = $this->getTags();
			$ecriture = 0;
			$lecture = 0;
			if ($userRights != 1) {
				foreach($userRights as $rights) {
					if ($ecriture && $lecture) {break;}
					if (in_array($rights["id_tag"],$fileTags)) {
						$ecriture = $rights["ecriture"];
						$lecture = $rights["lecture"];
					}
				}
			}
			return array("ecriture" => $ecriture,"lecture" => $lecture);
		}
		else {
			return array("ecriture" => 1,"lecture" => 1);
		}
	}

	//getteur
	public function getFileID(): int
	{
		return $this->id_fichier;
	}
	
	public function getAuthor(): string
	{
		return $this->auteur;
	}
	
	public function getAuthorName(): string
	{
		return $this->nom_auteur;
	}
	
	public function getAuthorDescription(): string
	{
		$connection = new DatabaseConnection();
		$result = $connection->get_user($this->getAuthor());
		return $result["descriptif"];
	}
	
	public function getFilename(): string
	{
		return $this->nom_fichier;
	}
	
	public function getPath(): string
	{
		return $this->source;
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
	
	public function getFileDuration(): string
	{
		return $this->duree;
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
	
	public function getWriting(): bool
	{
		return $this->ecriture;
	}
	
	public function getReading(): bool
	{
		return $this->lecture;
	}
	
	public function getDeleted(): bool
	{
		return $this->deleted;
	}
}