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
    private bool $supprime;

    public function __construct($id_fichier)
    {
        $this->id_fichier = $id_fichier;
		$this->auteur = $this->getAuthor($id_fichier);
		$this->source = $this->getPath($id_fichier);
		$this->nom_fichier = $this->getFilename($id_fichier);
		$this->date_publication = $this->getReleaseDate($id_fichier);
		$this->date_modification = $this->getModificationDate($id_fichier);
		$this->taille_Mo = $this->getFileSize($id_fichier);
		$this->type = $this->getFileType($id_fichier);
		$this->extension = $this->getFileExtension($id_fichier);
		$this->supprime = false;
    }
	
	public function getAuthor(int $id_fichier): string
	{
		return (new DatabaseConnection())->get_file($id_fichier)["email"];
	}
	
	public function getPath(int $id_fichier): string
	{
		$connection = new DatabaseConnection();
		return $connection->get_file($id_fichier)["source"] . '\\\\' . $connection->get_file($id_fichier)["nom_fichier"];
	}
	
	public function getFilename(int $id_fichier): string
	{
		return (new DatabaseConnection())->get_file($id_fichier)["nom_fichier"];
	}
	
	public function getReleaseDate(int $id_fichier): string
	{
		return (new DatabaseConnection())->get_file($id_fichier)["date_publication"];
	}
	
	public function getModificationDate(int $id_fichier): string
	{
		return (new DatabaseConnection())->get_file($id_fichier)["date_derniere_modification"];
	}
	
	public function getFileSize(int $id_fichier): float
	{
		return (new DatabaseConnection())->get_file($id_fichier)["taille_Mo"];
	}
	
	public function getFileType(int $id_fichier): string
	{
		return (new DatabaseConnection())->get_file($id_fichier)["type"];
	}
	
	public function getFileExtension(int $id_fichier): string
	{
		return (new DatabaseConnection())->get_file($id_fichier)["extension"];
	}
}