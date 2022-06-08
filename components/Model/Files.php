<?php

namespace Application\Model;

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
		$this->supprime = $this->getBasket($id_fichier);
    }
	
	public function getAuthor(int $id_fichier): string
	{
		
	}
	
	public function getPath(int $id_fichier): string
	{
		
	}
	
	public function getFilename(int $id_fichier): string
	{
		
	}
	
	public function getReleaseDate(int $id_fichier): string
	{
		
	}
	
	public function getModificationDate(int $id_fichier): string
	{
		
	}
	
	public function getFileSize(int $id_fichier): string
	{
		
	}
	
	public function getFileType(int $id_fichier): string
	{
		
	}
	
	public function getFileExtension(int $id_fichier): string
	{
		
	}
	
	public function getBasket(int $id_fichier): string
	{
		
	}
}