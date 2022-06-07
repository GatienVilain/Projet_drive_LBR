<?php

namespace Application\Tools\Database\Files;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

trait FilesEdit
{
	//ajoute un fichier, que l'utilisateur a mis, à la table fichier de la base de données
	function add_file(string $source, string $email, string $nom_fichier, float $taille, string $type, string $extension)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$date = date("Y-m-d");

		$query = $conn->prepare("INSERT INTO fichier (source,nom_fichier,email,date_publication,date_derniere_modification,taille_Mo,type,extension) VALUES (?,?,?,?,?,?,?,?)");
		$query->bind_param("sssssdss", $source, $nom_fichier, $email, $date, $date, $taille, $type, $extension);
		if (!$query->execute()) {
			$conn->close();
			return $this->console_log("Echec d'ajout du fichier à la base de donnée.");
		}
		$conn->close();

		return 0;
	}

	//supprime un fichier de la table fichier et de la table fichier_supprime de la base de donnée ainsi que du serveur
	function delete_file(int $id_fichier)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		//on regarde si le fichier existe et qu'il est dans la table fichier supprimé
		$query = $conn->prepare("SELECT * FROM fichier as f JOIN fichier_supprime AS fs ON f.id_fichier = fs.id_fichier WHERE fs.id_fichier = ?");
		$query->bind_param("i", $id_fichier);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		if ($result != NULL) {
			//s'il existe on le supprime
			$path = sprintf('%s\\%s.%s', $result["source"], $result["nom_fichier"], $result["extension"]);
			$query = $conn->prepare("DELETE FROM fichier WHERE id_fichier = ?");
			$query->bind_param("i", $id_fichier);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de suppression du fichierde la table fichier.");
			}
			$query = $conn->prepare("DELETE FROM fichier_supprime WHERE id_fichier = ?");
			$query->bind_param("i", $id_fichier);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de suppression du fichier de la table fichier supprimé.");
			}
			if (!unlink($path)) {
				$conn->close();
				return $this->console_log("Le fichier n'a pas pu être supprimé du serveur.");
			}
			$conn->close();
		} else {
			//sinon on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("Le fichier n'a pas pu être supprimé car il n'existe pas.");
		}
		return 0;
	}

	//renvoie les informations associées au fichier (nom_fichier, auteur, date de publication, date de dernière modification, taille_Mo, type, extension)
	function get_file(int $id_fichier)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		//on regarde si le fichier existe
		$query = $conn->prepare("SELECT nom_fichier,email,date_publication,date_derniere_modification,taille_Mo,type,extension FROM fichier WHERE id_fichier = ?");
		$query->bind_param("i", $id_fichier);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		if ($result != NULL) {
			//s'il existe, on renvoie les informations associées au fichier
			$conn->close();
			return $result;
		} else {
			//le fichier n'existe pas
			return $this->console_log("Le fichier n'existe pas.");
		}
	}

	//modifier le nom d'un fichier
	function modify_filename(int $id_fichier, string $nouveau_nom_fichier)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_file($id_fichier)) {
			//s'il existe, on modifie le nom associé au fichier
			$query = $conn->prepare("UPDATE fichier SET nom_fichier = ? WHERE id_fichier = ?");
			$query->bind_param("si", $nouveau_nom_fichier, $id_fichier);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de mise à jour du nom du fichier.");
			}
			$conn->close();
		} else {
			$conn->close();
			return $this->console_log("Le fichier n'existe pas.");
		}

		return 0;
	}
}