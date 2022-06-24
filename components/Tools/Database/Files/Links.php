<?php

namespace Application\Tools\Database\Files;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

trait FilesLinks
{
	//associe un tag à un fichier dans la table appartenir de la base de donnée
	function add_link(int $id_fichier, int $id_tag = 1)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_file($id_fichier) && $this->check_tag($id_tag)) {
			//on regarde si le fichier a déjà ce tag
			$query = $conn->prepare("SELECT * FROM appartenir WHERE id_fichier = ? AND id_tag = ?");
			$query->bind_param("ii", $id_fichier, $id_tag);
			$query->execute();
			$result = $query->get_result()->fetch_assoc();
			if ($result == NULL) {
				//si le fichier n'a pas ce tag, on lui associe
				$query = $conn->prepare("INSERT INTO appartenir (id_fichier,id_tag) VALUES (?,?)");
				$query->bind_param("ii", $id_fichier, $id_tag);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec d'association du tag au fichier.");
				}
				$conn->close();
			} else {
				//sinon on renvoie un message d'erreur
				$conn->close();
				return $this->console_log("Le fichier a déjà ce tag.");
			}
		} else {
			$conn->close();
			return $this->console_log("Le fichier ou le tag n'existe pas.");
		}
		return 0;
	}

	//supprime un tag associé à un fichier dans la table appartenir de la base de donnée
	function delete_link(int $id_fichier, int $id_tag)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_file($id_fichier) && $this->check_tag($id_tag)) {
			//on regarde si le fichier a ce tag
			$query = $conn->prepare("SELECT * FROM appartenir WHERE id_fichier = ? AND id_tag = ?");
			$query->bind_param("ii", $id_fichier, $id_tag);
			$query->execute();
			$result = $query->get_result()->fetch_assoc();
			if ($result != NULL) {
				//si le fichier a ce tag, on le supprime
				$query = $conn->prepare("DELETE FROM appartenir WHERE id_fichier = ? AND id_tag = ?");
				$query->bind_param("ii", $id_fichier, $id_tag);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de suppression du tag du fichier.");
				}
				$conn->close();
			} else {
				//sinon on renvoie un message d'erreur
				$conn->close();
				return $this->console_log("Ce tag n'est pas associé à ce fichier.");
			}
		} else {
			$conn->close();
			return $this->console_log("Le fichier ou le tag n'existe pas.");
		}
		return 0;
	}

	//renvoie les tags associés à un fichier
	function get_link(int $id_fichier)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_file($id_fichier)) {
			//on regarde tous les tags associés au fichier
			$query = $conn->prepare("SELECT id_tag FROM appartenir WHERE id_fichier = ?");
			$query->bind_param("i", $id_fichier);
			$query->execute();
			$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
			$conn->close();
			return $result;
		} else {
			$conn->close();
			return $this->console_log("Le fichier n'existe pas.");
		}
	}

	//renvoie la liste de tous les id_fichier, qui ne sont pas supprimés, associés à un utilisateur
	function get_files_of_user(string $email)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_user($email)) {

			$query = $conn->prepare("SELECT f.id_fichier FROM fichier AS f WHERE email = ? AND f.id_fichier NOT IN (SELECT fs.id_fichier FROM fichier_supprime AS fs)");
			$query->bind_param("s", $email);
			$query->execute();
			$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
			$conn->close();
			if ($result != NULL) {
				return $result;
			} else {
				return $this->console_log("L'utilisateur n'a pas de fichiers associés.");
			}
		} else {
			return $this->console_log("Le compte n'existe pas.");
		}
	}

	//renvoie la liste de tous les id_fichier, qui ne sont pas supprimés, liés à un id_tag
	function get_files_by_link(int $id_tag)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_tag($id_tag)) {

			$query = $conn->prepare("SELECT f.id_fichier FROM fichier AS f JOIN appartenir AS a ON f.id_fichier = a.id_fichier WHERE a.id_tag = ? AND f.id_fichier NOT IN (SELECT fs.id_fichier FROM fichier_supprime AS fs)");
			$query->bind_param("i", $id_tag);
			$query->execute();
			$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
			$conn->close();
			if ($result != NULL) {
				return $result;
			} else {
				return $this->console_log("Le tag n'est associé à aucun fichier.");
			}
		} else {
			return $this->console_log("Le tag n'existe pas.");
		}
	}
}
