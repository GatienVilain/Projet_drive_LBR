<?php

namespace Application\tests\Database\Files;

require_once("components/tools/Database/DatabaseUnitTest.php");

use Application\tools\Database\DatabaseUnitTest;

trait FilesEdit
{
	/** @test */
	public function Test_add_file()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->add_file('C:\\\\wamp64\\\\www\\\\Unit Test', "maxime.herbin@student.junia.com", "chat", 3.42, "image", "jpg");
		$this->assertEquals(0, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("SELECT * FROM fichier WHERE id_fichier = 1");
		$result = $query->fetch_assoc();

		$date = date('Y-m-d');

		$this->assertEquals(1, $result["id_fichier"]);
		$this->assertEquals("maxime.herbin@student.junia.com", $result["email"]);
		$this->assertEquals('C:\\\\wamp64\\\\www\\\\Unit Test', $result["source"]);
		$this->assertEquals("chat", $result["nom_fichier"]);
		$this->assertEquals($date, $result["date_publication"]);
		$this->assertEquals($date, $result["date_derniere_modification"]);
		$this->assertEquals(3.42, $result["taille_Mo"]);
		$this->assertEquals(null, $result["duree"]);
		$this->assertEquals("image", $result["type"]);
		$this->assertEquals("jpg", $result["extension"]);

		$conn->close();
	}

	/** @test */
	public function Test_delete_file()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->delete_file(10);
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$img = imagecreatetruecolor(50, 50);
		imagepng($img, 'C:\\\\wamp64\\\\www\\\\Unit Test\\\\test.png');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));

		$result = $sql->delete_file(1);
		$this->assertEquals(-1, $result);

		$query = $conn->query(sprintf("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (1,'%s')", $date));
		$result = $sql->delete_file(1);
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT * FROM fichier WHERE id_fichier = 1");
		$result = $query->fetch_assoc();
		$this->assertEquals(null, $result);

		$query = $conn->query("SELECT * FROM fichier_supprime WHERE id_fichier = 1");
		$result = $query->fetch_assoc();
		$this->assertEquals(null, $result);

		$conn->close();
	}

	/** @test */
	public function Test_get_file()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->get_file(10);
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));

		$result = $sql->get_file(1);

		$this->assertEquals("test", $result["nom_fichier"]);
		$this->assertEquals("C:\\\\wamp64\\\\www\\\\Unit Test", $result["source"]);
		$this->assertEquals("maxime.herbin@student.junia.com", $result["email"]);
		$this->assertEquals($date, $result["date_publication"]);
		$this->assertEquals($date, $result["date_derniere_modification"]);
		$this->assertEquals(0.05, $result["taille_Mo"]);
		$this->assertEquals(null, $result["duree"]);
		$this->assertEquals("image", $result["type"]);
		$this->assertEquals("png", $result["extension"]);

		$conn->close();
	}

	/** @test */
	public function Test_modify_filename()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->modify_filename(10, "test2");
		$this->assertEquals(-1, $result);

		$date = date('Y-m-d');

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));

		$result = $sql->modify_filename(1, "test2");
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT nom_fichier FROM fichier WHERE id_fichier = 1");
		$result = $query->fetch_assoc();

		$this->assertEquals("test2", $result["nom_fichier"]);

		$conn->close();
	}
	
	/** @test */
	public function Test_get_all_files()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();
		
		$result = $sql->get_all_files();
		$this->assertEquals(-1, $result);
		
		$date = date('Y-m-d');
		
		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", $date, $date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('celestin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test2',0.15,'%s','%s','video',mp4')", $date, $date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test3',0.20,'%s','%s','image','png')", $date, $date));
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (3,'2020-12-13')");

		$result = $sql->get_all_files();
		$this->assertEquals(0, $result);
		
		$this->assertEquals(2, count($result));
		$this->assertEquals('maxime.herbin@student.junia.com', $result[0]["email"]);
		$this->assertEquals('png', $result[0]["extension"]);
		$this->assertEquals('celestin@student.junia.com', $result[1]["email"]);
		$this->assertEquals("test2", $result[1]["nom_fichier"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_modify_file_date()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();
		
		$result = $sql->modify_file_date();
		$this->assertEquals(-1, $result);
		
		$date = date('Y-m-d');
		
		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')", '2020-12-03', '2020-12-03'));

		$result = $sql->modify_file_date();
		$this->assertEquals(0, $result);
		
		$query = $conn->query("SELECT * FROM fichier");
		$result = $query->fetch_assoc();
		
		$this->assertEquals($date, $result["date_derniere_modification"]);
		
		$conn->close();
	}
}