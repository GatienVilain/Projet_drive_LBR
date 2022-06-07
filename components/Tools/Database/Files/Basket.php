<?php

namespace Application\Tools\Database\Files;


trait Basket
{
	//met un fichier dans la corbeille (ajoute un fichier à la table fichier_supprime)
	function basket_file(int $id_fichier)
	{
		//point de connexion à la base de donnée
		$conn = new mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_file($id_fichier)) {
			$date = date("Y-m-d");
			//on regarde si le fichier est déjà dans la corbeille
			$query = $conn->prepare("SELECT * FROM fichier_supprime WHERE id_fichier = ?");
			$query->bind_param("i", $id_fichier);
			$query->execute();
			$result = $query->get_result()->fetch_assoc();
			if ($result == NULL) {
				//s'il ne l'est pas, on l'ajoute à la corbeille
				$query = $conn->prepare("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (?,?)");
				$query->bind_param("is", $id_fichier, $date);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de mise à la corbeille du fichier.");
				}
				$conn->close();
			} else {
				//sinon, on renvoie un message d'erreur
				$conn->close();
				return $this->console_log("Le fichier est déjà dans la corbeille.");
			}
		} else {
			$conn->close();
			return $this->console_log("Le fichier n'existe pas.");
		}
		return 0;
	}

	//renvoie une liste des informations de chaque fichier supprimé par l'utilisateur (si l'email de l'utilisateur n'est pas renseigné, renvoie par défaut tous les fichiers supprimés)
	function get_basket_file(string $email = NULL)
	{
		//point de connexion à la base de donnée
		$conn = new mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($email == NULL) {
			$query = $conn->prepare("SELECT fs.id_fichier,nom_fichier,email,date_publication,date_derniere_modification,taille_Mo,type,extension FROM fichier AS f JOIN fichier_supprime AS fs ON f.id_fichier = fs.id_fichier");
			$query->execute();
			$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
			$conn->close();
			if ($result != NULL) {
				return $result;
			} else {
				return $this->console_log("Erreur de récupération des fichiers supprimés");
			}
		} else {
			$query = $conn->prepare("SELECT fs.id_fichier,nom_fichier,email,date_publication,date_derniere_modification,taille_Mo,type,extension FROM fichier AS f JOIN fichier_supprime AS fs ON f.id_fichier = fs.id_fichier WHERE email = ?");
			$query->bind_param("s", $email);
			$query->execute();
			$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
			$conn->close();
			if ($result != NULL) {
				return $result;
			} else {
				return $this->console_log("Erreur de récupération des fichiers supprimés");
			}
			$conn->close();
		}
	}

	//restaure un fichier (supprime le fichier de la table fichier_supprime et l'ajoute à la table fichier)
	function recover_file(int $id_fichier)
	{
		//point de connexion à la base de donnée
		$conn = new mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_file($id_fichier)) {
			//on regarde si le fichier est dans la corbeille
			$query = $conn->prepare("SELECT * FROM fichier_supprime WHERE id_fichier = ?");
			$query->bind_param("i", $id_fichier);
			$query->execute();
			$result = $query->get_result()->fetch_assoc();
			if ($result != NULL) {
				//s'il est dans la corbeille, on le restaure
				$query = $conn->prepare("DELETE FROM fichier_supprime WHERE id_fichier = ?");
				$query->bind_param("i", $id_fichier);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de restauration du fichier.");
				}
				$conn->close();
			} else {
				//sinon, on renvoie un message d'erreur
				$conn->close();
				return $this->console_log("Le fichier n'est pas dans la corbeille.");
			}
		} else {
			$conn->close();
			return $this->console_log("Le fichier n'existe pas.");
		}
		return 0;
	}

	//vérifie les fichiers de la corbeille, si la date de suppression est supérieur à 30 jours, supprime le fichier de la base de donnée et du serveur
	function basket_check()
	{
		//point de connexion à la base de donnée
		$conn = new mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$date = date('Y-m-d', strtotime('-30 days'));

		$query = $conn->prepare("SELECT id_fichier FROM fichier_supprime WHERE date_suppression < ? ");
		$query->bind_param("s", $date);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows > 0) {
			while ($row_data = $result->fetch_assoc()) {
				$this->delete_file($row_data["id_fichier"]);
			}
			$result->close();
			$conn->close();
		} else {
			$conn->close();
			return $this->console_log("Echec de récupération des fichiers.");
		}

		return 0;
	}
}