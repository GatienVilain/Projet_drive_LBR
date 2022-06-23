<?php

namespace Application\tests\Database\Tags;

require_once("components/tools/Database/DatabaseUnitTest.php");

use Application\tools\Database\DatabaseUnitTest;

trait TagsRights
{
	/** @test */
	public function Test_add_writing_right()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->add_writing_right("maxime.herbin@student.junia.com", 10);
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)", $date);
		$result = $conn->query($query);
		$result = $sql->add_writing_right("maxime.herbin@student.junia.com", 10);
		$this->assertEquals(-1, $result);

		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('chat')");
		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('maxime.herbin@student.junia.com',2,0,1)");

		$result = $sql->add_writing_right("maxime.herbin@student.junia.com", 1);
		$this->assertEquals(0, $result);
		$query = $conn->query("SELECT * FROM attribuer WHERE id_tag = 1");
		$result = $query->fetch_assoc();
		$this->assertEquals(1, $result["ecriture"]);
		$this->assertEquals(1, $result["lecture"]);

		$result = $sql->add_writing_right("maxime.herbin@student.junia.com", 2);
		$this->assertEquals(0, $result);
		$query = $conn->query("SELECT * FROM attribuer WHERE id_tag = 2");
		$result = $query->fetch_assoc();
		$this->assertEquals(1, $result["ecriture"]);
		$this->assertEquals(1, $result["lecture"]);

		$result = $sql->add_writing_right("maxime.herbin@student.junia.com", 1);
		$this->assertEquals(-1, $result);

		$conn->close();
	}

	/** @test */
	public function Test_add_reading_right()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->add_reading_right("maxime.herbin@student.junia.com", 10);
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)", $date);
		$result = $conn->query($query);
		$result = $sql->add_reading_right("maxime.herbin@student.junia.com", 10);
		$this->assertEquals(-1, $result);

		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		$result = $sql->add_reading_right("maxime.herbin@student.junia.com", 1);
		$this->assertEquals(0, $result);
		$query = $conn->query("SELECT * FROM attribuer");
		$result = $query->fetch_assoc();
		$this->assertEquals(0, $result["ecriture"]);
		$this->assertEquals(1, $result["lecture"]);

		$result = $sql->add_reading_right("maxime.herbin@student.junia.com", 1);
		$this->assertEquals(-1, $result);

		$conn->close();
	}

	/** @test */
	public function Test_modify_rights()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->modify_rights("maxime.herbin@student.junia.com", 10, 0, 1);
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)", $date);
		$result = $conn->query($query);
		$result = $sql->modify_rights("maxime.herbin@student.junia.com", 10, 0, 1);
		$this->assertEquals(-1, $result);

		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('maxime.herbin@student.junia.com',1,0,1)");

		$result = $sql->modify_rights("maxime.herbin@student.junia.com", 1, 1, 0);
		$this->assertEquals(-1, $result);

		$result = $sql->modify_rights("maxime.herbin@student.junia.com", 1, 1, 1);
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT ecriture,lecture FROM attribuer WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();

		$this->assertEquals(1, $result["ecriture"]);
		$this->assertEquals(1, $result["lecture"]);

		$conn->close();
	}

	/** @test */
	public function Test_delete_rights()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->delete_rights("maxime.herbin@student.junia.com", 10);
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)", $date);
		$result = $conn->query($query);
		$result = $sql->delete_rights("maxime.herbin@student.junia.com", 10);
		$this->assertEquals(-1, $result);

		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('maxime.herbin@student.junia.com',1,0,1)");

		$result = $sql->delete_rights("maxime.herbin@student.junia.com", 1);
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT * FROM attribuer WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();

		$this->assertEquals(null, $result);

		$conn->close();
	}

	/** @test */
	public function Test_get_rights()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->get_rights("maxime.herbin@student.junia.com", 10);
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)", $date);
		$result = $conn->query($query);
		$result = $sql->get_rights("maxime.herbin@student.junia.com", 10);
		$this->assertEquals(-1, $result);

		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('maxime.herbin@student.junia.com',1,0,1)");

		$result = $sql->get_rights("maxime.herbin@student.junia.com", 1);
		$this->assertEquals(0, $result["ecriture"]);
		$this->assertEquals(1, $result["lecture"]);

		$conn->close();
	}
	
	/** @test */
	public function Test_get_rights_of_user()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$date = date('Y-m-d');

		$result = $sql->get_rights_of_user("test");
		$this->assertEquals(-1, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)", $date));

		$result = $sql->get_rights_of_user('maxime.herbin@student.junia.com');
		$this->assertEquals(-1, $result);

		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('maxime.herbin@student.junia.com',1,1,1)");
		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('maxime.herbin@student.junia.com',2,0,1)");
		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('celestin.captal@student.junia.com',3,1,1)");

		$result = $sql->get_rights_of_user('maxime.herbin@student.junia.com');

		$this->assertEquals(2, count($result));

		$this->assertEquals(1, $result[0]["id_tag"]);
		$this->assertEquals(1, $result[0]["ecriture"]);
		$this->assertEquals(1, $result[0]["lecture"]);

		$this->assertEquals(2, $result[1]["id_tag"]);
		$this->assertEquals(0, $result[1]["ecriture"]);
		$this->assertEquals(1, $result[1]["lecture"]);

		$conn->close();
	}
}
