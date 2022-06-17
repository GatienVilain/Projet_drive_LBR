<?php

namespace Application\Tools\Database\Tags;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

trait TagEdit
{

    //ajoute un tag  dans la table tag de la base de donnée et l'associe à une catégorie
	function add_tag(string $nom_tag, string $nom_categorie_tag = "autres")
	{
		if($nom_tag == '') {
			return $this->console_log("Le nom du tag est incorrect.");
		}
		else if ($this->check_tag_category_link($nom_tag,$nom_categorie_tag)){
			return $this->console_log("Un tag du même nom est associé à cette catégorie.");
		}
		
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}
		//on ajoute le tag
		$query = $conn->prepare("INSERT INTO tag (nom_tag) VALUES (?)");
		$query->bind_param("s", $nom_tag);
		if (!$query->execute()) {
			$conn->close();
			return $this->console_log("Echec de création du tag.");
		}
		//on l'associe à sa catégorie
		$query = $conn->prepare("INSERT INTO caracteriser (id_tag,nom_categorie_tag) VALUES (?,?)");
		$query->bind_param("ss", $id_tag, $nom_categorie_tag);
		if (!$query->execute()) {
			$conn->close();
			return $this->console_log("Echec d'association du tag à la catégorie.");
		}
		$conn->close();
		return 0;
	}

	//modifie le nom d'un tag  $config : array("nom_tag" => nom), associe le tag à une catégorie $config : array("nom_categorie_tag" => nom)
	function modify_tag(int $id_tag, array $config)
	{
		$default_config = array("nom_tag" => null, "nom_categorie_tag" => null);
		
		$configs = array_merge($default_config, $config);
		
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}
		
		if ($this->check_tag($id_tag)) {
			//si on modifie les deux, on vérifie qu'il n'y ait pas déjà un tag du même nouveau nom dans la nouvelle catégorie
			if ($configs["nom_tag"] != null && $configs["nom_categorie_tag"] != null && !$this->check_tag_category_link($configs["nom_tag"],$configs["nom_categorie_tag"])) { 
				
				$query = $conn->prepare("UPDATE caracteriser SET nom_categorie_tag = ? WHERE id_tag = ?");
				$query->bind_param("si", $configs["nom_categorie_tag"], $id_tag);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de mise à jour de la catégorie du tag.");
				}
				
				$query = $conn->prepare("UPDATE tag SET nom_tag = ? WHERE id_tag = ?");
				$query->bind_param("si", $configs["nom_tag"], $id_tag);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de mise à jour du nom du tag.");
				}
			}
			//si on ne modifie que le nom, on vérifie que le nouveau nom n'est pas déjà présent dans la catégorie
			else if ($configs["nom_tag"] != null && !$this->check_tag_category_link($configs["nom_tag"],$this->check_tag_category($id_tag)["nom_categorie_tag"])) {
				
				$query = $conn->prepare("UPDATE tag SET nom_tag = ? WHERE id_tag = ?");
				$query->bind_param("si", $configs["nom_tag"], $id_tag);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de mise à jour du nom du tag.");
				}
			}
			//si on ne modifie que la catégorie, on vérifie que le nouveau nom n'est pas déjà présent dans la nouvelle catégorie
			else if ($configs["nom_categorie_tag"] != null && !$this->check_tag_category_link($this->get_tag($id_tag)["nom_tag"],$this->check_tag_category($id_tag)["nom_categorie_tag"])) {
				
				$query = $conn->prepare("UPDATE caracteriser SET nom_categorie_tag = ? WHERE id_tag = ?");
				$query->bind_param("si", $configs["nom_categorie_tag"], $id_tag);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de mise à jour de la catégorie du tag.");
				}
			}
			//sinon on renvoie un message d'erreur
			else {
				$conn->close();
				return $this->console_log("Aucun changement n'a été appliqué au tag.");
			}
		}
		$conn->close();
		return 0;
	}

	//supprime un tag
	function delete_tag(int $id_tag)
	{
		if ($id_tag == 1) {
			return $this->console_log("Le tag 'sans tags' ne peut pas être supprimé.");
		}
		
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

	//renvoie le nom du tag à partir de son identifiant
	function get_tag(int $id_tag) {
		
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_tag($id_tag)){
			$query = $conn->prepare("SELECT nom_tag FROM tag WHERE id_tag = ?");
			$query->bind_param("i", $id_tag);
			$query->execute();
			$result = $query->get_result()->fetch_assoc();
			$conn->close();
			if ($result != NULL) {
				return $result;
			}
			else {
				return $this->console_log("Echec de récupération des catégories de tags.");
			}
		}
		else {
			return $this->console_log("Le tag n'existe pas.");
		}
	}

	    //renvoie tous les id_tag à partir du nom de leur catégorie
		function get_tag_by_category(string $nom_categorie_tag) {
			//point de connexion à la base de donnée
			$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
			if (!$conn) {
				return $this->console_log("Echec de connexion à la base de donnée.");
			}
			if ($this->check_tag_category($nom_categorie_tag)){
				$query = $conn->prepare("SELECT id_tag FROM caracteriser WHERE nom_categorie_tag = ?");
				$query->bind_param("s", $nom_categorie_tag);
				$query->execute();
				$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
				$conn->close();
				if ($result != NULL) {
					return $result;
				}
				else {
					return $this->console_log("Echec de récupération des id tags.");
				}
			}
			else {
				return $this->console_log("La catégorie de tag n'existe pas.");
			}
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
			//si la catégorie existe, on la supprime
			$query = $conn->prepare("DELETE FROM categorie_tag WHERE nom_categorie_tag = ?");
			$query->bind_param("s", $nom_categorie_tag);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de suppression d'une catégorie de tag.");
			}

			//on associe tous les tags de cette catégorie 

		} else {
			//sinon on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("La catégorie de tag existe déjà.");
		}
		return 0;
	}

	//renvoie toutes les catégories de tag si l'id du tag n'est pas précisé
	function get_tag_category(int $id_tag = 0)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ( $id_tag == 0) {
			$query = $conn->prepare("SELECT * FROM categorie_tag");
		}
		else if($this->check_tag($id_tag)){
			$query = $conn->prepare("SELECT nom_categorie_tag FROM caracteriser WHERE id_tag = ?");
			$query->bind_param("i",$id_tag);
		}
		else {
			return $this->console_log("Le tag n'existe pas.");
		}

		$query->execute();
		$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
		$conn->close();
		if ($result != NULL) {
			return $result;
		} 
		else {
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

		if ($this->check_tag_category($nom_categorie_tag) && !$this->check_tag_category($nouveau_nom_categorie_tag)) {
			//si la catégorie existe et que son nouveau nom n'est pas celui d'une catégorie existante, on modifie le nom de la catégorie de tag dans la table catégorie_tag
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