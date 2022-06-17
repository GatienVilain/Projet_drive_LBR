<?php

namespace Application\Tools\Database;

require_once("components/Tools/Database/DatabaseUser.php");
require_once("components/Tools/Database/Files/Basket.php");
require_once("components/Tools/Database/Files/Edit.php");
require_once("components/Tools/Database/Files/Links.php");
require_once("components/Tools/Database/Tags/Edit.php");
require_once("components/Tools/Database/Tags/Rights.php");

use Application\Tools\Database\DatabaseUser;
use Application\Tools\Database\Files\Basket;
use Application\Tools\Database\Files\FilesEdit;
use Application\Tools\Database\Files\FilesLinks;
use Application\Tools\Database\Tags\TagEdit;
use Application\Tools\Database\Tags\TagsRights;


class DatabaseConnection
{
	protected const host = "localhost";
	protected const user = "root";
	protected const password = "dorian";
	protected const db = "drive";


	use DatabaseUser;
	use Basket;
	use FilesEdit;
	use FilesLinks;
	use TagEdit;
	use TagsRights;


	function console_log($output, $with_script_tags = true)
	{
		$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
			');';
		if ($with_script_tags) {
			$js_code = '<script>' . $js_code . '</script>';
		}

		//décommenter la ligne ci-dessous pour aider à débugger
		//echo $js_code;
		return -1;
	}


	//renvoie true si l'utilisateur est dans la BDD, false sinon
	function check_user(string $email): bool
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$query = $conn->prepare("SELECT * FROM utilisateur WHERE email = ?");
		$query->bind_param("s", $email);
		$query->execute();
		$result = $query->get_result();

		return $result->num_rows > 0;
	}


	//renvoie true si le fichier est dans la BDD, false sinon
	function check_file(int $id_fichier): bool
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$query = $conn->prepare("SELECT * FROM fichier WHERE id_fichier = ?");
		$query->bind_param("i", $id_fichier);
		$query->execute();
		$result = $query->get_result();

		return $result->num_rows > 0;
	}


	//renvoie true si le tag est dans la BDD, false sinon
	function check_tag(int $id_tag): bool
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$query = $conn->prepare("SELECT * FROM tag WHERE id_tag = ?");
		$query->bind_param("s", $id_tag);
		$query->execute();
		$result = $query->get_result();

		return $result->num_rows > 0;
	}


	//renvoie true si la catégorie de tag est dans la BDD, false sinon
	function check_tag_category(string $nom_categorie_tag): bool
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$query = $conn->prepare("SELECT * FROM categorie_tag WHERE nom_categorie_tag = ?");
		$query->bind_param("s", $nom_categorie_tag);
		$query->execute();
		$result = $query->get_result();

		return $result->num_rows > 0;
	}
	
	//renvoie true s'il le nom_tag existe dans la catégorie nom_categorie_tag
	function check_tag_category_link(string $nom_tag, string $nom_categorie_tag): bool
	{
		//point de connexion à la base de donnée
		$conn = new \mysqli(DatabaseConnection::host, DatabaseConnection::user, DatabaseConnection::password, DatabaseConnection::db);
		if (!$conn) {
			return $this->console_log("Echec de connexion à la base de donnée.");
		}

		$query = $conn->prepare("SELECT * FROM caracteriser AS c JOIN tag AS t ON c.id_tag = t.id_tag WHERE nom_tag = ? AND nom_categorie_tag = ?");
		$query->bind_param("ss", $nom_tag, $nom_categorie_tag);
		$query->execute();
		$result = $query->get_result();

		return $result->num_rows > 0;
	}
}
