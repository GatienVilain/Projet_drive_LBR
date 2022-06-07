<?php

namespace Application\Tools\Database\Files;


trait FilesLinks
{
    //associe un tag à un fichier dans la table appartenir de la base de donnée
	function add_link(int $id_fichier, int $id_tag = 1)
	{
		//point de connexion à la base de donnée
		$conn = new mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
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
					return $this->console_log("Echec d'attribution du tag au fichier.");
				}
				$conn->close();
			} else {
				//sinon on renvoie un message d'erreur
				$conn->close();
				return $this->console_log("Ce tag est déjà attribué au fichier.");
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
		$conn = new mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
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
		$conn = new mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
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
			if ($result != NULL) {
				return $result;
			} else {
				return $this->console_log("Echec de récupération des tags associés au fichier.");
			}
		} else {
			$conn->close();
			return $this->console_log("Le fichier n'existe pas.");
		}
	}
}