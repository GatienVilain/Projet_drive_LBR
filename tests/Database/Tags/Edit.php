<?php

namespace Application\tests\Database\Tags;

require_once("components/tools/Database/DatabaseUnitTest.php");

use Application\tools\Database\DatabaseUnitTest;

trait TagEdit
{
	/** @test */
	public function Test_add_tag()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->add_tag("");
		$this->assertEquals(-1, $result);

		$result = $sql->add_tag("Dorian");
		$this->assertEquals(0, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("SELECT * FROM caracteriser JOIN tag ON caracteriser.id_tag = tag.id_tag WHERE tag.id_tag = 1");
		$result = $query->fetch_assoc();

		$this->assertEquals("Dorian", $result["nom_tag"]);
		$this->assertEquals("autres", $result["nom_categorie_tag"]);

		$result = $sql->add_tag("Dorian");
		$this->assertEquals(-1, $result);
		
		$result = $sql->add_tag("Dorian", "dfhgertyq");
		$this->assertEquals(-1, $result);
		
		$query = $conn->query("INSERT INTO catergorie_tag (nom_categorie_tag) VALUES (test)");
		
		$result = $sql->add_tag("Dorian", "test");
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT * FROM caracteriser JOIN tag ON caracteriser.id_tag = tag.id_tag WHERE tag.id_tag = 2");
		$result = $query->fetch_assoc();

		$this->assertEquals("Dorian", $result["nom_tag"]);
		$this->assertEquals("autres", $result["nom_categorie_tag"]);

		$conn->close();
	}

	/** @test */
	public function Test_modify_tag()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->modify_tag(1,("nom_tag" => "test"));
		$this->assertEquals(-1, $result);
		
		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('test')");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('categorietest')");
		$query = $conn->query("INSERT INTO caracteriser (id_tag,nom_categorie_tag) VALUES (1,'categorietest')");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('categorietest2')");
		
		$result = $sql->modify_tag(1,("nom_categorie_tag" => "test"));
		$this->assertEquals(-1, $result);
		
		$result = $sql->modify_tag(1,("nom_tag" => "test2","nom_categorie_tag" => "categorietest2"));
		$this->assertEquals(0, $result);
		
		$query = $conn->query("SELECT t.nom_tag,c.nom_categorie_tag FROM caracteriser AS c JOIN tag AS t ON c.id_tag = t.id_tag WHERE t.id_tag = 1");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("test2", $result["nom_tag"]);
		$this->assertEquals("categorietest2", $result["nom_categorie_tag"]);
		
		$conn->close();
	}

	/** @test */
	public function Test_delete_tag()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->delete_tag(10);
		$this->assertEquals(-1, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('Chat')");
		$query = $conn->query("INSERT INTO caracteriser (id_tag,nom_categorie_tag) VALUES (1,'Animal')");
		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('test',1,1,1)");
		$query = $conn->query("INSERT INTO appartenir (id_fichier,id_tag) VALUES (1,1)");

		$result = $sql->delete_tag(1);
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT * FROM tag");
		$result = $query->fetch_assoc();
		$this->assertEquals(false, $result);

		$query = $conn->query("SELECT * FROM caracteriser");
		$result = $query->fetch_assoc();
		$this->assertEquals(false, $result);

		$query = $conn->query("SELECT * FROM attribuer");
		$result = $query->fetch_assoc();
		$this->assertEquals(false, $result);

		$query = $conn->query("SELECT * FROM appartenir");
		$result = $query->fetch_assoc();
		$this->assertEquals(false, $result);

		$conn->close();
	}

	/** @test */
	public function Test_get_tag()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->get_tag(10);
		$this->assertEquals(-1, $result);
		
		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('Chat')");
		
		$result = $sql->get_tag(1);
		$this->assertEquals("Chat", $result["nom_tag"]);
		
		$conn->close();
	}

	/** @test */
	public function Test_add_tag_category()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->add_tag_category("autres");
		$this->assertEquals(0, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("SELECT * FROM categorie_tag");
		$result = $query->fetch_assoc();

		$this->assertEquals("autres", $result["nom_categorie_tag"]);

		$conn->close();
	}

	/** @test */
	public function Test_delete_tag_category()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->delete_tag_category("autres");
		$this->assertEquals(-1, $result);

		$result = $sql->delete_tag_category("test");
		$this->assertEquals(-1, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('test')");
		$query = $conn->query("INSERT INTO caracteriser (id_tag,nom_categorie_tag) VALUES (1,'test')");

		$result = $sql->delete_tag_category("test");
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT * FROM caracteriser");
		$result = $query->fetch_assoc();

		$this->assertEquals("autres", $result["nom_categorie_tag"]);

		$conn->close();
	}

	/** @test */
	public function Test_get_tag_category()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->get_tag_category();
		$this->assertEquals(-1, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('test')");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('test2')");

		$result = $sql->get_tag_category();

		$this->assertEquals("test", $result[0]["nom_categorie_tag"]);
		$this->assertEquals("test2", $result[1]["nom_categorie_tag"]);

		$conn->close();
	}
	
	/** @test */
	public function Test_get_tag_by_category()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->get_tag_by_category("test");
		$this->assertEquals(-1, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('Animaux')");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('Canidés')");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('Chat')");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('Chien')");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('Cheval')");
		$query = $conn->query("INSERT INTO caracteriser (id_tag,nom_categorie_tag) VALUES (1,'Animaux')");
		$query = $conn->query("INSERT INTO caracteriser (id_tag,nom_categorie_tag) VALUES (2,'Canidés')");
		$query = $conn->query("INSERT INTO caracteriser (id_tag,nom_categorie_tag) VALUES (3,'Animaux')");

		$result = $sql->get_tag_by_category("Animaux");

		$this->assertEquals(1, $result[0]["id_tag"]);
		$this->assertEquals(3, $result[1]["id_tag"]);

		$conn->close();
	}

	/** @test */
	public function Test_modify_tag_category_name()
	{
		$this->clear_DB();
		$sql = new DatabaseConnection();

		$result = $sql->modify_tag_category_name("Chat", "Chien");
		$this->assertEquals(-1, $result);

		$conn = new \mysqli("localhost", "root", "dorian", "driveTest");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('Chat')");
		$query = $conn->query("INSERT INTO caracteriser (id_tag,nom_categorie_tag) VALUES (1,'Chat')");

		$result = $sql->modify_tag_category_name("Chat", "Chien");
		$this->assertEquals(0, $result);

		$query = $conn->query("SELECT * FROM categorie_tag");
		$result = $query->fetch_assoc();

		$this->assertEquals("Chien", $result["nom_categorie_tag"]);

		$query = $conn->query("SELECT * FROM caracteriser");
		$result = $query->fetch_assoc();

		$this->assertEquals("Chien", $result["nom_categorie_tag"]);

		$conn->close();
	}
}