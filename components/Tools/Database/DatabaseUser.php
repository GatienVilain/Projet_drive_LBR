<?php

namespace Application\Tools\Database;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

trait DatabaseUser
{
	//ajoute un utilisateur à la table utilisateur de la base de donnée, renvoie un message d'erreur en cas d'échec
	function add_user(string $email, string $prenom, string $nom, string $mdp, string $descriptif, string $role)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$date = date("Y-m-d");

		//on regarde si le compte n'est pas déjà dans la base de donnée
		$query = $conn->prepare("SELECT compte_supprime FROM utilisateur WHERE email = ?");
		$query->bind_param("s", $email);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();

		if ($result != NULL) {
			//il existe donc on regarde s'il a été supprimé
			if ($result["compte_supprime"]) {
				//s'il a été supprimé, on met à jour le compte
				$query = $conn->prepare("UPDATE utilisateur SET mot_de_passe = ?,descriptif = ?,role = ?,date_inscription = ?,compte_supprime = 0 WHERE email = ?");
				$query->bind_param("sssss", $mdp, $descriptif, $date, $role, $email);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de mis à jour de la base de donnée.");
				}
				$conn->close();
			} else {
				//s'il n'a pas été supprimé, on renvoie un message d'erreur
				$conn->close();
				return $this->console_log("Le compte existe déjà.");
			}
		} else {
			//sinon on créé le compte
			$query = $conn->prepare("INSERT INTO utilisateur (email, prenom, nom, mot_de_passe, descriptif,role,date_inscription,compte_supprime) VALUES (?,?,?,?,?,?,?,0)");
			$query->bind_param("sssssss", $email, $prenom, $nom, $mdp, $descriptif, $role, $date);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de création d'un nouvel utilisateur.");
			}
			$conn->close();
		}
		return 0;
	}

	//supprime le mot de passe et la date d'inscription et passe le compte utilisateur en supprimé, renvoie un message d'erreur en cas d'échec
	function delete_user(string $email)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}
		//on regarde si le compte existe
		if ($this->check_user($email)) {
			//on regarde si ce n'est pas le dernier compte admin supprimé
			$query = $conn->prepare("SELECT COUNT(*) AS admin_restant FROM utilisateur WHERE role = 'admin' AND compte_supprime = 0");
			$query->execute();
			$result = $query->get_result()->fetch_assoc()["admin_restant"];
			if ($result == 1) {
				$conn->close();
				return $this->console_log("Echec de suppression de l'utilisateur, on ne peut pas supprimer tous les admins");
			}

			// si ce n'est pas le cas, on supprime le mot de passe, le rôle, la date d'inscription et on passe le compte en supprimé
			$query = $conn->prepare("UPDATE utilisateur SET mot_de_passe = null, role = null,date_inscription = null, compte_supprime = 1 WHERE email = ?");
			$query->bind_param("s", $email);
			if (!$query->execute()) {
				$conn->close();
				return $this->console_log("Echec de suppression du compte.");
			}
			$conn->close();
		} else {
			$conn->close();
			return $this->console_log("Le compte que vous voulez supprimé n'existe pas.");
		}
		return 0;
	}

	//renvoie les informations de l'utilisateur (Prénom, Nom, Description, Rôle, Date d'inscription, 0 si le compte n'est pas supprimé ou 1 s'il l'est)
	function get_user(string $email)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$query = $conn->prepare("SELECT prenom,nom,descriptif,role,date_inscription,compte_supprime FROM utilisateur WHERE email = ?");
		$query->bind_param("s", $email);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		//on regarde si le compte existe
		if ($result != NULL) {
			//s'il existe, on renvoie les informations de l'utilisateur
			$conn->close();
			return $result;
		} else {
			return $this->console_log("Le compte n'existe pas.");
		}
	}

	//renvoie le mot de passe de l'utilisateur
	function get_user_password(string $email)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$query = $conn->prepare("SELECT mot_de_passe FROM utilisateur WHERE email = ?");
		$query->bind_param("s", $email);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		//on regarde si le compte existe
		if ($result != NULL) {
			//s'il existe, on renvoie les informations de l'utilisateur
			$conn->close();
			return $result;
		} else {
			return $this->console_log("Le compte n'existe pas.");
		}
	}

	//modifie le nom et prénom de l'utilisateur, config: array(prenom => NULL,nom => NULL,mot_de_passe => NULL, descriptif => NULL,role => NULL), NULL par défaut
	function update_user($email, $config)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$default_config = array("prenom" => NULL, "nom" => NULL, "mot_de_passe" => NULL, "descriptif" => NULL, "role" => NULL);

		$configs = array_merge_recursive($default_config, $config);

		if ($this->check_user($email)) {
			//on modifie le prénom et/ou
			if ($configs['prenom'] != NULL) {
				$query = $conn->prepare("UPDATE utilisateur SET prenom = ? WHERE email = ?");
				$query->bind_param("ss", $config['prenom'], $email);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de mise à jour du prénom de l'utilisateur.");
				}
			}

			//on modifie le nom et/ou
			if ($configs['nom'] != NULL) {
				$query = $conn->prepare("UPDATE utilisateur SET nom = ? WHERE email = ?");
				$query->bind_param("ss", $config['nom'], $email);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de mise à jour du nom de l'utilisateur.");
				}
			}

			//on modifie le mot de passe et/ou
			if ($configs['mot_de_passe'] != NULL) {
				$query = $conn->prepare("UPDATE utilisateur SET mot_de_passe = ? WHERE email = ?");
				$query->bind_param("ss", $config['mot_de_passe'], $email);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de mise à jour du mot de passe de l'utilisateur.");
				}
			}

			//on modifie la description et/ou
			if ($configs['descriptif'] != NULL) {
				$query = $conn->prepare("UPDATE utilisateur SET descriptif = ? WHERE email = ?");
				$query->bind_param("ss", $config['descriptif'], $email);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de mise à jour de la description de l'utilisateur.");
				}
			}

			//on modifie le rôle
			if ($configs['role'] != NULL) {
				$query = $conn->prepare("UPDATE utilisateur SET role = ? WHERE email = ?");
				$query->bind_param("ss", $config['role'], $email);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de mise à jour du rôle de l'utilisateur.");
				}
			}
		} else {
			$conn->close();
			return $this->console_log("Le compte n'existe pas.");
		}

		return 0;
	}

	//renvoie la liste de tous les emails des utilisateurs dont le compte n'est pas supprimé
	function get_all_users()
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$query = $conn->prepare("SELECT email FROM utilisateur WHERE compte_supprime = 0");
		$query->execute();
		$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
		$conn->close();
		if ($result != NULL) {
			return $result;
		} else {
			return $this->console_log("Echec de récupération des utilisateurs.");
		}
	}
}
