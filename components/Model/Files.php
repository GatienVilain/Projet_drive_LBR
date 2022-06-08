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
		return $connection->get_file($id_fichier)["source"] . '\\' . $connection->get_file($id_fichier)["nom_fichier"];
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
					$ecriture = $connection->get_rights($_SESSION["email"],$tags[$i])["ecriture"];
					$lecture = $connection->get_rights($_SESSION["email"],$tags[$i])["lecture"];
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
		return $this->date_derniere_modification;
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
	
	public function getWriting(): int
	{
		return $this->ecriture;
	}
	
	public function getReading(): int
	{
		return $this->lecture;
	}
	
	public function preview(): string
	{
		$image = sprintf("<div class=miniature><div class=image> <img src='%s'/></div> <div class = titre> <p> %s </p> </div></div>",$this->getPath() . '.' . $this->getFileExtension(),$this->getFilename());
		return $image;
	}
}