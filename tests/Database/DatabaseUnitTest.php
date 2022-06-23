<?php

namespace Application\tests\DatabaseUnitTest;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/tests/Database/DatabaseUser.php");
require_once("components/tests/Database/Files/Basket.php");
require_once("components/tests/Database/Files/Edit.php");
require_once("components/tests/Database/Files/Links.php");
require_once("components/tests/Database/Tags/Edit.php");
require_once("components/tests/Database/Tags/Rights.php");

use Application\Tools\Database\DatabaseConnection;
use Application\tests\Database\DatabaseUser;
use Application\tests\Database\Files\Basket;
use Application\tests\Database\Files\FilesEdit;
use Application\tests\Database\Files\FilesLinks;
use Application\tests\Database\Tags\TagEdit;
use Application\tests\Database\Tags\TagsRights;

use PHPUnit\Framework\TestCase;

class DatabaseUnitTest extends TestCase
{
	//nettoie la BDD pour le prochain test
	public function clear_DB()
	{
		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("TRUNCATE TABLE utilisateur");
		$query = $conn->query("TRUNCATE TABLE fichier");
		$query = $conn->query("TRUNCATE TABLE fichier_supprime");
		$query = $conn->query("TRUNCATE TABLE tag");
		$query = $conn->query("TRUNCATE TABLE categorie_tag");
		$query = $conn->query("TRUNCATE TABLE caracteriser");
		$query = $conn->query("TRUNCATE TABLE appartenir");
		$query = $conn->query("TRUNCATE TABLE attribuer");
		$conn->close();
	}
	
	/** @test */
	public function Test_check_user()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->check_user("test");
		$this->assertEquals(false, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','2022-06-01',0)");

		$result = $sql->check_user("maxime.herbin@student.junia.com");
		$this->assertEquals(true, $result);

		$conn->close();
	}

	/** @test */
	public function Test_check_file()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->check_file(1);
		$this->assertEquals(false, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));

		$result = $sql->check_file(1);
		$this->assertEquals(true, $result);

		$conn->close();
	}

	/** @test */
	public function Test_check_tag()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->check_tag(1);
		$this->assertEquals(false, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");

		$result = $sql->check_tag(1);
		$this->assertEquals(true, $result);

		$conn->close();
	}

	/** @test */
	public function Test_check_tag_category()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->check_tag_category("test");
		$this->assertEquals(false, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('test')");

		$result = $sql->check_tag_category("test");
		$this->assertEquals(true, $result);

		$conn->close();
	}
}