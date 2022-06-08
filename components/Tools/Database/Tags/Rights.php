<?php

namespace Application\Tools\Database\Tags;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

trait TagsRights
{
	//ajoute le droit d'écriture (implique droit de lecture) à un utilisateur par rapport au tag associé dans la table attribuer de la base de données
	function add_writing_right(string $email, int $id_tag)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_user($email) && $this->check_tag($id_tag)) {
			//on regarde si l'utilisateur a des droits par rapport à ce tag
			$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND id_tag = ?");
			$query->bind_param("si", $email, $id_tag);
			$query->execute();
			$result = $query->get_result()->fetch_assoc();
			if ($result != NULL) {
				//s'il en a, on regarde s'il a le droit d'écriture
				$conn->close();
				if ($result["ecriture"]) {
					//si c'est le cas, on renvoie un message d'erreur
					return $this->console_log("L'utilisateur a déjà le droit d'écriture sur ce tag.");
				} else {
					//sinon on modifie ses droits sur le tag
					return $this->modify_rights($email, $id_tag, 1, 1);
				}
			} else {
				//sinon on lui créé le droit d'écriture sur le tag
				$query = $conn->prepare("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES (?,?,1,1)");
				$query->bind_param("si", $email, $id_tag);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec d'attribution du droit d'écriture sur le tag");
				}
				$conn->close();
			}
		} else {
			$conn->close();
			return $this->console_log("Le compte ou le tag n'existe pas.");
		}
		return 0;
	}

	//ajoute le droit de lecture à un utilisateur par rapport au tag associé dans la table attribuer de la base de données
	function add_reading_right(string $email, int $id_tag)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_user($email) && $this->check_tag($id_tag)) {
			//on regarde si l'utilisateur a des droits par rapport à ce tag
			$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND id_tag = ?");
			$query->bind_param("si", $email, $id_tag);
			$query->execute();
			$result = $query->get_result()->fetch_assoc();
			if ($result != NULL && $result["lecture"]) {
				//s'il en a, on regarde s'il a le droit de lecture
				//si c'est le cas, on renvoie un message d'erreur
				$conn->close();
				return $this->console_log("L'utilisateur a déjà le droit de lecture sur ce tag.");
			} else {
				//sinon on lui créé le droit de lecture sur le tag
				$query = $conn->prepare("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES (?,?,0,1)");
				$query->bind_param("si", $email, $id_tag);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec d'attribution du droit de lecture sur le tag");
				}
				$conn->close();
			}
		} else {
			$conn->close();
			return $this->console_log("Le compte ou le tag n'existe pas.");
		}
		return 0;
	}

	//modifie les droit d'écriture ou de lecture à un utilisateur par rapport au tag associé dans la table attribuer de la base de données
	function modify_rights(string $email, int $id_tag, int $ecriture, int $lecture)
	{
		if ($ecriture == 1 && $lecture != 1) {
			return $this->console_log("L'attribution de droit du droit d'écriture doit impliqué le droit de lecture sur le tag.");
		}

		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_user($email) && $this->check_tag($id_tag)) {
			//on regarde si l'utilisateur a des droits par rapport à ce tag
			$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND id_tag = ?");
			$query->bind_param("si", $email, $id_tag);
			$query->execute();
			$result = $query->get_result()->fetch_assoc();
			if ($result != NULL && ($result["ecriture"] != $ecriture || $result["lecture"] != $lecture)) {
				//s'il a des droits sur ce tag et qu'ils sont différents de la modification à apporter, on modifie les droits
				$query = $conn->prepare("UPDATE attribuer SET ecriture = ?, lecture = ? WHERE email = ? AND id_tag = ?");
				$query->bind_param("iisi", $ecriture, $lecture, $email, $id_tag);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de modification des droits sur le tag");
				}
				$conn->close();
			} else {
				//sinon on renvoie un message d'erreur
				$conn->close();
				return $this->console_log("L'utilisateur n'a aucun droits sur ce tag ou aucune modification n'est nécessaire.");
			}
		} else {
			$conn->close();
			return $this->console_log("Le compte ou le tag n'existe pas.");
		}
		return 0;
	}

	//supprime tous les droits d'un utilisateur par rapport au tag associé dans la table attribuer de la base de données
	function delete_rights(string $email, int $id_tag)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_user($email) && $this->check_tag($id_tag)) {
			//on regarde si l'utilisateur a des droits par rapport à ce tag
			$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND id_tag = ?");
			$query->bind_param("si", $email, $id_tag);
			$query->execute();
			$result = $query->get_result()->fetch_assoc();
			if ($result != NULL) {
				//si c'est le cas, on les supprime
				$query = $conn->prepare("DELETE FROM attribuer WHERE email = ? AND id_tag = ?");
				$query->bind_param("si", $email, $id_tag);
				if (!$query->execute()) {
					$conn->close();
					return $this->console_log("Echec de suppression des droits sur le tag");
				}
				$conn->close();
			} else {
				//sinon on renvoie un message d'erreur
				$conn->close();
				return $this->console_log("L'utilisateur n'a aucun droits sur ce tag");
			}
		} else {
			$conn->close();
			return $this->console_log("Le compte ou le tag n'existe pas.");
		}
		return 0;
	}

	//renvoie les droits de l'utilisateur par rapport au tag associé
	function get_rights(string $email, int $id_tag)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_user($email) && $this->check_tag($id_tag)) {
			//on regarde si l'utilisateur a des droits par rapport à ce tag
			$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND id_tag = ?");
			$query->bind_param("si", $email, $id_tag);
			$query->execute();
			$result = $query->get_result()->fetch_assoc();
			if ($result != NULL) {
				//si c'est le cas, on renvoie les droits de l'utilisateur par rapport au tag
				$conn->close();
				return $result;
			} else {
				//sinon on renvoie un message d'erreur
				return $this->console_log("L'utilisateur n'a aucun droits sur ce tag");
			}
		} else {
			$conn->close();
			return $this->console_log("Le compte ou le tag n'existe pas.");
		}
		return 0;
	}

	//renvoie tous les droits d'un utilisateur sous la forme d'un tableau de la forme : array(("id_tag" => 1,"ecriture" => 1,"lecture" => 1),("id_tag" => 2,"ecriture" => 0,"lecture" => 1))
	function get_links_of_user(string $email)
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		if ($this->check_user($email)) {

			$query = $conn->prepare("SELECT id_tag,ecriture,lecture FROM attribuer WHERE email = ?");
			$query->bind_param("s", $email);
			$query->execute();
			$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
			$conn->close();
			if ($result != NULL) {
				return $result;
			} else {
				return $this->console_log("L'utilisateur n'a aucun droits sur les tags.");
			}
		} else {
			return $this->console_log("Le compte n'existe pas.");
		}
	}
}
