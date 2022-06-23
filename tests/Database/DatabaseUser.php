<?php

namespace Application\tests\Database;

require_once("components/tools/Database/DatabaseUnitTest.php");

use Application\tools\Database\DatabaseUnitTest;

trait DatabaseUser
{
	/** @test */
	public function Test_add_user()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->add_user("maxime.herbin@student.junia.com", "Maxime", "Herbin", "123456789", "Salut !", "admin");
		$this->assertEquals(0, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("SELECT prenom,nom,mot_de_passe,descriptif,role,date_inscription FROM utilisateur");
		$result = $query->fetch_assoc();

		$date = date('Y-m-d');

		$this->assertEquals("Maxime", $result["prenom"]);
		$this->assertEquals("Herbin", $result["nom"]);
		$this->assertEquals("123456789", $result["mot_de_passe"]);
		$this->assertEquals("Salut !", $result["descriptif"]);
		$this->assertEquals("admin", $result["role"]);
		$this->assertEquals($date, $result["date_inscription"]);

		$result = $sql->add_user("maxime.herbin@student.junia.com", "Dorian", "Larouziere", "train", "yo !", "admin");
		$this->assertEquals(-1, $result);

		$query = $conn->query("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('celestin.captal@student.junia.com','Celestin','Captal','1234','','invite',0");
		$query = $conn->query("UPDATE utilisateur SET compte_supprime = 1 WHERE email = 'celestin.captal@student.junia.com'");

		$result = $sql->add_user("celestin.captal@student.junia.com", "Celestin", "Captal", "test", "oui", "admin");
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT mot_de_passe,descriptif,role FROM utilisateur WHERE email = 'celestin.captal@student.junia.com'");
		$result = $query->fetch_assoc();

		$this->assertEquals("test", $result["mot_de_passe"]);
		$this->assertEquals("oui", $result["descriptif"]);
		$this->assertEquals("admin", $result["role"]);

		$conn->close();
	}

	/** @test */
	public function Test_delete_user()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->delete_user("maxime.herbin@student.junia.com");
		$this->assertEquals(-1, $result);


		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('celestin.captal@student.junia.com','Celestin','Captal','1234','','admin','2022-06-01',0)");
		$result = $sql->delete_user("celestin.captal@student.junia.com");
		$this->assertEquals(-1, $result);

		$query = $conn->query("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','2022-06-01',0)");
		$result = $sql->delete_user("maxime.herbin@student.junia.com");
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT mot_de_passe,role,date_inscription,compte_supprime FROM utilisateur WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();
		$this->assertEquals(NULL, $result["mot_de_passe"]);
		$this->assertEquals(NULL, $result["role"]);
		$this->assertEquals(NULL, $result["date_inscription"]);
		$this->assertEquals(1, $result["compte_supprime"]);

		$conn->close();
	}

	/** @test */
	public function Test_get_user()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->get_user("maxime.herbin@student.junia.com");
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)", $date);
		$result = $conn->query($query);
		$result = $sql->get_user("maxime.herbin@student.junia.com");

		$this->assertEquals("Maxime", $result["prenom"]);
		$this->assertEquals("Herbin", $result["nom"]);
		$this->assertEquals("", $result["descriptif"]);
		$this->assertEquals("admin", $result["role"]);
		$this->assertEquals($date, $result["date_inscription"]);
		$this->assertEquals(0, $result["compte_supprime"]);

		$conn->close();
	}

	/** @test */
	public function Test_get_user_password()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->get_user("maxime.herbin@student.junia.com");
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)", $date);
		$result = $conn->query($query);
		$result = $sql->get_user_password("maxime.herbin@student.junia.com");

		$this->assertEquals('1234', $result["mot_de_passe"]);

		$conn->close();
	}

	/** @test */
	public function Test_update_user()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$options = array("prenom" => "Paul");

		$result = $sql->update_user("maxime.herbin@student.junia.com", $options);
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)", $date);
		$result = $conn->query($query);
		$result = $sql->update_user("maxime.herbin@student.junia.com", $options);
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT prenom,nom,mot_de_passe,descriptif,role FROM utilisateur WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();

		$this->assertEquals("Paul", $result["prenom"]);
		$this->assertEquals("Herbin", $result["nom"]);
		$this->assertEquals("1234", $result["mot_de_passe"]);
		$this->assertEquals("", $result["descriptif"]);
		$this->assertEquals("admin", $result["role"]);

		$options = array("nom" => "Gabelle");

		$result = $sql->update_user("maxime.herbin@student.junia.com", $options);
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT prenom,nom,mot_de_passe,descriptif,role FROM utilisateur WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();

		$this->assertEquals("Paul", $result["prenom"]);
		$this->assertEquals("Gabelle", $result["nom"]);
		$this->assertEquals("1234", $result["mot_de_passe"]);
		$this->assertEquals("", $result["descriptif"]);
		$this->assertEquals("admin", $result["role"]);

		$options = array("mot_de_passe" => "9876");

		$result = $sql->update_user("maxime.herbin@student.junia.com", $options);
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT prenom,nom,mot_de_passe,descriptif,role FROM utilisateur WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();

		$this->assertEquals("Paul", $result["prenom"]);
		$this->assertEquals("Gabelle", $result["nom"]);
		$this->assertEquals("9876", $result["mot_de_passe"]);
		$this->assertEquals("", $result["descriptif"]);
		$this->assertEquals("admin", $result["role"]);

		$options = array("descriptif" => "salut.");

		$result = $sql->update_user("maxime.herbin@student.junia.com", $options);
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT prenom,nom,mot_de_passe,descriptif,role FROM utilisateur WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();

		$this->assertEquals("Paul", $result["prenom"]);
		$this->assertEquals("Gabelle", $result["nom"]);
		$this->assertEquals("9876", $result["mot_de_passe"]);
		$this->assertEquals("salut.", $result["descriptif"]);
		$this->assertEquals("admin", $result["role"]);

		$options = array("role" => "invite");

		$result = $sql->update_user("maxime.herbin@student.junia.com", $options);
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT prenom,nom,mot_de_passe,descriptif,role FROM utilisateur WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();

		$this->assertEquals("Paul", $result["prenom"]);
		$this->assertEquals("Gabelle", $result["nom"]);
		$this->assertEquals("9876", $result["mot_de_passe"]);
		$this->assertEquals("salut.", $result["descriptif"]);
		$this->assertEquals("invite", $result["role"]);

		$conn->close();
	}
	
	/** @test */
	public function Test_get_all_users()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$date = date('Y-m-d');

		$result = $sql->get_all_users();
		$this->assertEquals(-1, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)", $date));
		$query = $conn->query(sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('celestin.captal@student.junia.com','Celestin','Captal','1234','','admin','%s',0)", $date));

		$result = $sql->get_all_users();

		$this->assertEquals('maxime.herbin@student.junia.com', $result[0]["email"]);
		$this->assertEquals('celestin.captal@student.junia.com', $result[1]["email"]);

		$conn->close();
	}
}
