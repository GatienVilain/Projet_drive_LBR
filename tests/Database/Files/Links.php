<?php

namespace Application\tests\Database\Files;

require_once("components/tools/Database/DatabaseUnitTest.php");

use Application\tools\Database\DatabaseUnitTest;

trait FilesLinks
{
	/** @test */
	public function Test_add_link()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->add_link(10);
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));

		$result = $sql->add_link(1);
		$this->assertEquals(-1, $result);

		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");

		$result = $sql->add_link(1);
		$this->assertEquals(0, $result);

		$result = $sql->add_link(1, 1);
		$this->assertEquals(-1, $result);

		$query = $conn->query("SELECT * FROM appartenir");
		$result = $query->fetch_assoc();

		$this->assertEquals(1, $result["id_fichier"]);
		$this->assertEquals(1, $result["id_tag"]);

		$conn->close();
	}

	/** @test */
	public function Test_delete_link()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->delete_link(10, 1);
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));

		$result = $sql->delete_link(1, 3);
		$this->assertEquals(-1, $result);

		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		$query = $conn->query("INSERT INTO appartenir (id_fichier,id_tag) VALUES (1,1)");

		$result = $sql->delete_link(1, 1);
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT * FROM appartenir");
		$result = $query->fetch_assoc();

		$this->assertEquals(false, $result);

		$conn->close();
	}

	/** @test */
	public function Test_get_link()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->get_link(10);
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));

		$result = $sql->get_link(1);
		$this->assertEquals(true, empty($result["id_tag"]));

		$query = $conn->query("INSERT INTO appartenir (id_fichier,id_tag) VALUES (1,1)");
		$query = $conn->query("INSERT INTO appartenir (id_fichier,id_tag) VALUES (1,2)");

		$result = $sql->get_link(1);

		$this->assertEquals(1, $result[0]["id_tag"]);
		$this->assertEquals(2, $result[1]["id_tag"]);

		$conn->close();
	}
	
	/** @test */
	public function Test_get_files_of_user()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$date = date('Y-m-d');

		$result = $sql->get_files_of_user("test");
		$this->assertEquals(-1, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)", $date));
		$query = $conn->query(sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('celestin.captal@student.junia.com','Celestin','Captal','1234','','admin','%s',0)", $date));

		$result = $sql->get_files_of_user('maxime.herbin@student.junia.com');
		$this->assertEquals(-1, $result);

		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test2',40,'%s','%s','image','png')", $date, $date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('celestin.captal@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test3',40,'%s','%s','image','png')", $date, $date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('celestin.captal@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test4',40,'%s','%s','image','png')", $date, $date));
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (1,'2020-12-13')");

		$result = $sql->get_files_of_user('maxime.herbin@student.junia.com');

		$this->assertEquals(1, count($result));
		$this->assertEquals(2, $result[0]["id_fichier"]);

		$result = $sql->get_files_of_user('celestin.captal@student.junia.com');
		$this->assertEquals(2, count($result));
		$this->assertEquals(3, $result[0]["id_fichier"]);
		$this->assertEquals(4, $result[1]["id_fichier"]);

		$conn->close();
	}
	
	/** @test */
	public function Test_get_files_by_link()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$date = date('Y-m-d');

		$result = $sql->get_files_by_link(1);
		$this->assertEquals(-1, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");

		$result = $sql->get_files_by_link(1);
		$this->assertEquals(-1, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test2',40,'%s','%s','image','png')", $date, $date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('celestin.captal@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test3',40,'%s','%s','image','png')", $date, $date));
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (1,'2020-12-13')");
		$query = $conn->query("INSERT INTO appartenir (id_fichier,id_tag) VALUES (1,1)");
		$query = $conn->query("INSERT INTO appartenir (id_fichier,id_tag) VALUES (2,1)");
		$query = $conn->query("INSERT INTO appartenir (id_fichier,id_tag) VALUES (3,1)");

		$result = $sql->get_files_by_link(1);

		$this->assertEquals(2, count($result));
		$this->assertEquals(2, $result[0]["id_fichier"]);
		$this->assertEquals(3, $result[1]["id_fichier"]);

		$conn->close();
	}
}
