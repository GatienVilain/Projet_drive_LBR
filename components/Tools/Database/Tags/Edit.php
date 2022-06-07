<?php

namespace Application\Tools\Database\Tags;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

trait TagEdit
{

    //ajoute un tag  dans la table tag de la base de donnée
	function add_tag(string $nom_tag)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$query = $conn->prepare("INSERT INTO tag (nom_tag) VALUES (?)");
		$query->bind_param("s", $nom_tag);
		if (!$query->execute()) {
			$conn->close();
			return $this->console_log("Echec de création du tag.");
		}
		$conn->close();
		return 0;
	}

	//modifie le nom d'un tag
	function modify_tag_name(int $id_tag, string $nouveau_nom_tag)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_tag($id_tag)) {
			//on modifie le nom du tag dans la table tag
			$query = $conn->prepare("UPDATE tag SET nom_tag = ? WHERE id_tag = ?");
			$query->bind_param("si", $nouveau_nom_tag, $id_tag);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de mise à jour du nom du tag.");
			}
		} else {
			$conn->close();
			return $this->console_log("Le tag n'existe pas.");
		}
		return 0;
	}

	//supprime un tag
	function delete_tag(int $id_tag)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}


		if ($this->check_tag($id_tag)) {
			//si le tag existe, on le suprime de la table tag, caracteriser, attribuer et appartenir
			$query = $conn->prepare("DELETE FROM tag WHERE id_tag = ?");
			$query->bind_param("i", $id_tag);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de suppression du tag de la table tag.");
			}

			$query = $conn->prepare("DELETE FROM caracteriser WHERE id_tag = ?");
			$query->bind_param("i", $id_tag);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de suppression du tag de la table categorie de tag.");
			}

			$query = $conn->prepare("DELETE FROM attribuer WHERE id_tag = ?");
			$query->bind_param("i", $id_tag);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de suppression du tag de la table attribuer.");
			}

			$query = $conn->prepare("DELETE FROM appartenir WHERE id_tag = ?");
			$query->bind_param("i", $id_tag);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de suppression du tag de la table appartenir.");
			}
		} else {
			$conn->close();
			return $this->console_log("Ce tag n'existe pas.");
		}
		return 0;
	}

	//ajoute une catégorie de tag à la table categorie_tag de la base de donnée
	function add_tag_category(string $nom_categorie_tag)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if (!$this->check_tag_category($nom_categorie_tag)) {
			//si la catégorie n'existe pas, on la créé
			$query = $conn->prepare("INSERT INTO categorie_tag (nom_categorie_tag) VALUES (?)");
			$query->bind_param("s", $nom_categorie_tag);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de création d'une catégorie de tag.");
			}
			$conn->close();
		} else {
			//sinon on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("La catégorie de tag existe déjà.");
		}
		return 0;
	}

	//supprime une catégorie de tag de la table categorie_tag de la base de donnée
	function delete_tag_category(string $nom_categorie_tag)
	{
		if ($nom_categorie_tag == "autres") {
			return $this->console_log("La catégorie 'autres' ne peut pas être supprimé.");
		}

		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_tag_category($nom_categorie_tag)) {
			//si la catégorie existe, on la supprime et tous les tags de cette catégorie vont dans la catégorie "autres"
			$query = $conn->prepare("DELETE FROM categorie_tag WHERE nom_categorie_tag = ?");
			$query->bind_param("s", $nom_categorie_tag);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de suppression d'une catégorie de tag.");
			}
			$query = $conn->prepare("UPDATE caracteriser SET nom_categorie_tag = 'autres' WHERE nom_categorie_tag = ?");
			$query->bind_param("s", $nom_categorie_tag);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de mise à jour de la table tag.");
			}
			$conn->close();
		} else {
			//sinon on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("La catégorie de tag existe déjà.");
		}
		return 0;
	}

	//renvoie toutes les catégories de tag
	function get_tag_category()
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$query = $conn->prepare("SELECT * FROM categorie_tag");
		$query->execute();
		$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
		$conn->close();
		if ($result != NULL) {
			return $result;
		} else {
			return $this->console_log("Echec de récupération des catégories de tags.");
		}
	}

	//modifie le nom d'une catégorie de tag
	function modify_tag_category_name(string $nom_categorie_tag, string $nouveau_nom_categorie_tag)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_tag_category($nom_categorie_tag)) {
			//si la catégorie existe, on modifie le nom de la catégorie de tag dans la table catégorie_tag
			$query = $conn->prepare("UPDATE categorie_tag SET nom_categorie_tag = ? WHERE nom_categorie_tag = ?");
			$query->bind_param("ss", $nouveau_nom_categorie_tag, $nom_categorie_tag);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de mise à jour du nom de la catégorie de tag dans la table categorie_tag.");
			}

			//on modifie le nom de la catégorie de tag dans la table caractériser
			$query = $conn->prepare("UPDATE caracteriser SET nom_categorie_tag = ? WHERE nom_categorie_tag = ?");
			$query->bind_param("ss", $nouveau_nom_categorie_tag, $nom_categorie_tag);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de mise à jour du nom de la catégorie de tag dans la table tag.");
			}
		} else {
			//sinon, on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("La catégorie de tag n'existe pas.");
		}
		return 0;
	}
}