<?php

namespace Application\tests\Database\Files;

require_once("components/tools/Database/DatabaseUnitTest.php");

use Application\tools\Database\DatabaseUnitTest;

trait Basket
{
	/** @test */
	public function Test_basket_file()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->basket_file(1);
		$this->assertEquals(-1, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (1,'2020-12-13')");

		$result = $sql->basket_file(1);
		$this->assertEquals(-1, $result);

		$query = $conn->query("DELETE FROM fichier_supprime WHERE id_fichier = 1");

		$date = date('Y-m-d');
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));

		$result = $sql->basket_file(1);
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT * FROM fichier_supprime");
		$result = $query->fetch_assoc();

		$this->assertEquals(1, $result["id_fichier"]);
		$this->assertEquals($date, $result["date_suppression"]);

		$conn->close();
	}

	/** @test */
	public function Test_get_basket_file()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->get_basket_file();
		$this->assertEquals(-1, $result);

		$result = $sql->get_basket_file("celestin.captal@student.junia.com");
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('celestin.captal@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test2',40,'%s','%s','image','png')", $date, $date));
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (1,'2020-12-13')");
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (2,'2020-12-13')");

		$result = $sql->get_basket_file();
		$this->assertEquals("maxime.herbin@student.junia.com", $result[0]["email"]);
		$this->assertEquals("celestin.captal@student.junia.com", $result[1]["email"]);

		$result = $sql->get_basket_file("maxime.herbin@student.junia.com");
		$this->assertEquals("maxime.herbin@student.junia.com", $result[0]["email"]);

		$conn->close();
	}

	/** @test */
	public function Test_recover_file()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->recover_file(1);
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));

		$result = $sql->recover_file(1);
		$this->assertEquals(-1, $result);

		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (1,'2020-12-13')");

		$result = $sql->recover_file(1);
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT * FROM fichier_supprime");
		$result = $query->fetch_assoc();
		$this->assertEquals(null, $result);

		$conn->close();
	}

	/** @test */
	public function Test_basket_check()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$date = date('Y-m-d');

		$img = imagecreatetruecolor(50, 50);
		imagepng($img, "C:\\\\wamp64\\\\www\\\\Unit Test\\\\test.png");

		$img = imagecreatetruecolor(50, 50);
		imagepng($img, "C:\\\\wamp64\\\\www\\\\Unit Test\\\\test2.png");

		$img = imagecreatetruecolor(50, 50);
		imagepng($img, "C:\\\\wamp64\\\\www\\\\Unit Test\\\\test3.png");

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('celestin.captal@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test2',40,'%s','%s','image','png')", $date, $date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('celestin.captal@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test3',40,'%s','%s','image','png')", $date, $date));
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (1,'2020-12-13')");
		$query = $conn->query(sprintf("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (2,'%s')", $date));
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (3,'2020-12-13')");

		$result = $sql->basket_check();
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT * FROM fichier_supprime");
		$result = $query->fetch_assoc();
		$this->assertEquals(2, $result["id_fichier"]);

		$query = $conn->query("SELECT * FROM fichier");
		$result = $query->fetch_assoc();
		$this->assertEquals(2, $result["id_fichier"]);

		$conn->close();
	}
}