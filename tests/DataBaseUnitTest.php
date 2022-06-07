<?php

namespace Test\Database;

require_once("components/Tools/Database/Database.php");

use Application\Tools\Database\Database;
use PHPUnit\Framework\TestCase;

class DataBaseUnitTest extends TestCase
{
	
	//nettoie la BDD pour le prochain test
	public function clear_DB()
	{
		$conn = new mysqli("localhost","root","dorian","driveTest");
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
		$sql = new Database();
		
		$result = $sql->check_user("test");
		$this->assertEquals(false,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','2022-06-01',0)");	
		
		$result = $sql->check_user("maxime.herbin@student.junia.com");
		$this->assertEquals(true,$result);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_check_file()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->check_file(1);
		$this->assertEquals(false,$result);
		
		$date = date('Y-m-d');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')",$date,$date));

		$result = $sql->check_file(1);
		$this->assertEquals(true,$result);

		$conn->close();
	}
	
	/** @test */
	public function Test_check_tag()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->check_tag(1);
		$this->assertEquals(false,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");

		$result = $sql->check_tag(1);
		$this->assertEquals(true,$result);

		$conn->close();
	}
	
	/** @test */
	public function Test_check_tag_category()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->check_tag_category("test");
		$this->assertEquals(false,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('test')");

		$result = $sql->check_tag_category("test");
		$this->assertEquals(true,$result);

		$conn->close();
	}
	
	/** @test */
	public function Test_add_user()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->add_user("maxime.herbin@student.junia.com","Maxime","Herbin","123456789","Salut !","admin");
		$this->assertEquals(0,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("SELECT prenom,nom,mot_de_passe,descriptif,role,date_inscription FROM utilisateur");
		$result = $query->fetch_assoc();
		
		$date = date('Y-m-d');
		
		$this->assertEquals("Maxime",$result["prenom"]);
		$this->assertEquals("Herbin",$result["nom"]);
		$this->assertEquals("123456789",$result["mot_de_passe"]);
		$this->assertEquals("Salut !",$result["descriptif"]);
		$this->assertEquals("admin",$result["role"]);
		$this->assertEquals($date,$result["date_inscription"]);
		
		$result = $sql->add_user("maxime.herbin@student.junia.com","Dorian","Larouziere","train","yo !","admin");
		$this->assertEquals(-1,$result);
		
		$query = $conn->query("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('celestin.captal@student.junia.com','Celestin','Captal','1234','','invite',0");
		$query = $conn->query("UPDATE utilisateur SET compte_supprime = 1 WHERE email = 'celestin.captal@student.junia.com'");
		
		$result = $sql->add_user("celestin.captal@student.junia.com","Celestin","Captal","test","oui","admin");
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT mot_de_passe,descriptif,role FROM utilisateur WHERE email = 'celestin.captal@student.junia.com'");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("test",$result["mot_de_passe"]);
		$this->assertEquals("oui",$result["descriptif"]);
		$this->assertEquals("admin",$result["role"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_delete_user()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->delete_user("maxime.herbin@student.junia.com");
		$this->assertEquals(-1,$result);
		
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('celestin.captal@student.junia.com','Celestin','Captal','1234','','admin','2022-06-01',0)");
		$result = $sql->delete_user("celestin.captal@student.junia.com");
		$this->assertEquals(-1,$result);
		
		$query = $conn->query("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','2022-06-01',0)");	
		$result = $sql->delete_user("maxime.herbin@student.junia.com");
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT mot_de_passe,role,date_inscription,compte_supprime FROM utilisateur WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();
		$this->assertEquals(NULL,$result["mot_de_passe"]);
		$this->assertEquals(NULL,$result["role"]);
		$this->assertEquals(NULL,$result["date_inscription"]);
		$this->assertEquals(1,$result["compte_supprime"]);
		
		$conn->close();
	}

 	/** @test */
	public function Test_get_user()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->get_user("maxime.herbin@student.junia.com");
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');

		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)",$date);
		$result = $conn->query($query);	
		$result = $sql->get_user("maxime.herbin@student.junia.com");
		
		$this->assertEquals("Maxime",$result["prenom"]);
		$this->assertEquals("Herbin",$result["nom"]);
		$this->assertEquals("",$result["descriptif"]);
		$this->assertEquals("admin",$result["role"]);
		$this->assertEquals($date,$result["date_inscription"]);		
		$this->assertEquals(0,$result["compte_supprime"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_get_user_password()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->get_user("maxime.herbin@student.junia.com");
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');

		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)",$date);
		$result = $conn->query($query);	
		$result = $sql->get_user_password("maxime.herbin@student.junia.com");
		
		$this->assertEquals('1234',$result["mot_de_passe"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_update_user()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$options = array("prenom" => "Paul");
		
		$result = $sql->update_user("maxime.herbin@student.junia.com",$options);
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');

		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)",$date);
		$result = $conn->query($query);	
		$result = $sql->update_user("maxime.herbin@student.junia.com",$options);
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT prenom,nom,mot_de_passe,descriptif,role FROM utilisateur WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("Paul",$result["prenom"]);
		$this->assertEquals("Herbin",$result["nom"]);
		$this->assertEquals("1234",$result["mot_de_passe"]);
		$this->assertEquals("",$result["descriptif"]);
		$this->assertEquals("admin",$result["role"]);
		
		$options = array("nom" => "Gabelle");
		
		$result = $sql->update_user("maxime.herbin@student.junia.com",$options);
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT prenom,nom,mot_de_passe,descriptif,role FROM utilisateur WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("Paul",$result["prenom"]);
		$this->assertEquals("Gabelle",$result["nom"]);
		$this->assertEquals("1234",$result["mot_de_passe"]);
		$this->assertEquals("",$result["descriptif"]);
		$this->assertEquals("admin",$result["role"]);
		
		$options = array("mot_de_passe" => "9876");
		
		$result = $sql->update_user("maxime.herbin@student.junia.com",$options);
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT prenom,nom,mot_de_passe,descriptif,role FROM utilisateur WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("Paul",$result["prenom"]);
		$this->assertEquals("Gabelle",$result["nom"]);
		$this->assertEquals("9876",$result["mot_de_passe"]);
		$this->assertEquals("",$result["descriptif"]);
		$this->assertEquals("admin",$result["role"]);
		
		$options = array("descriptif" => "salut.");
		
		$result = $sql->update_user("maxime.herbin@student.junia.com",$options);
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT prenom,nom,mot_de_passe,descriptif,role FROM utilisateur WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("Paul",$result["prenom"]);
		$this->assertEquals("Gabelle",$result["nom"]);
		$this->assertEquals("9876",$result["mot_de_passe"]);
		$this->assertEquals("salut.",$result["descriptif"]);
		$this->assertEquals("admin",$result["role"]);
		
		$options = array("role" => "invite");
		
		$result = $sql->update_user("maxime.herbin@student.junia.com",$options);
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT prenom,nom,mot_de_passe,descriptif,role FROM utilisateur WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("Paul",$result["prenom"]);
		$this->assertEquals("Gabelle",$result["nom"]);
		$this->assertEquals("9876",$result["mot_de_passe"]);
		$this->assertEquals("salut.",$result["descriptif"]);
		$this->assertEquals("invite",$result["role"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_add_writing_right()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->add_writing_right("maxime.herbin@student.junia.com",10);
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');

		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)",$date);
		$result = $conn->query($query);			
		$result = $sql->add_writing_right("maxime.herbin@student.junia.com",10);
		$this->assertEquals(-1,$result);
		
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('chat')");
		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('maxime.herbin@student.junia.com',2,0,1)");
		
		$result = $sql->add_writing_right("maxime.herbin@student.junia.com",1);
		$this->assertEquals(0,$result);
		$query = $conn->query("SELECT * FROM attribuer WHERE id_tag = 1");
		$result = $query->fetch_assoc();
		$this->assertEquals(1,$result["ecriture"]);
		$this->assertEquals(1,$result["lecture"]);
		
		$result = $sql->add_writing_right("maxime.herbin@student.junia.com",2);
		$this->assertEquals(0,$result);
		$query = $conn->query("SELECT * FROM attribuer WHERE id_tag = 2");
		$result = $query->fetch_assoc();
		$this->assertEquals(1,$result["ecriture"]);
		$this->assertEquals(1,$result["lecture"]);
		
		$result = $sql->add_writing_right("maxime.herbin@student.junia.com",1);
		$this->assertEquals(-1,$result);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_add_reading_right()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->add_reading_right("maxime.herbin@student.junia.com",10);
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)",$date);
		$result = $conn->query($query);			
		$result = $sql->add_reading_right("maxime.herbin@student.junia.com",10);
		$this->assertEquals(-1,$result);
		
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		$result = $sql->add_reading_right("maxime.herbin@student.junia.com",1);
		$this->assertEquals(0,$result);
		$query = $conn->query("SELECT * FROM attribuer");
		$result = $query->fetch_assoc();
		$this->assertEquals(0,$result["ecriture"]);
		$this->assertEquals(1,$result["lecture"]);
		
		$result = $sql->add_reading_right("maxime.herbin@student.junia.com",1);
		$this->assertEquals(-1,$result);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_modify_rights()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->modify_rights("maxime.herbin@student.junia.com",10,0,1);
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)",$date);
		$result = $conn->query($query);			
		$result = $sql->modify_rights("maxime.herbin@student.junia.com",10,0,1);
		$this->assertEquals(-1,$result);
		
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('maxime.herbin@student.junia.com',1,0,1)");
		
		$result = $sql->modify_rights("maxime.herbin@student.junia.com",1,1,0);
		$this->assertEquals(-1,$result);
		
		$result = $sql->modify_rights("maxime.herbin@student.junia.com",1,1,1);
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT ecriture,lecture FROM attribuer WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();
		
		$this->assertEquals(1,$result["ecriture"]);
		$this->assertEquals(1,$result["lecture"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_delete_rights()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->delete_rights("maxime.herbin@student.junia.com",10);
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)",$date);
		$result = $conn->query($query);			
		$result = $sql->delete_rights("maxime.herbin@student.junia.com",10);
		$this->assertEquals(-1,$result);
		
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('maxime.herbin@student.junia.com',1,0,1)");
		
		$result = $sql->delete_rights("maxime.herbin@student.junia.com",1);
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT * FROM attribuer WHERE email = 'maxime.herbin@student.junia.com'");
		$result = $query->fetch_assoc();
		
		$this->assertEquals(null,$result);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_get_rights()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->get_rights("maxime.herbin@student.junia.com",10);
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = sprintf("INSERT INTO utilisateur (email,prenom,nom,mot_de_passe,descriptif,role,date_inscription,compte_supprime) VALUES ('maxime.herbin@student.junia.com','Maxime','Herbin','1234','','admin','%s',0)",$date);
		$result = $conn->query($query);			
		$result = $sql->get_rights("maxime.herbin@student.junia.com",10);
		$this->assertEquals(-1,$result);
		
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('maxime.herbin@student.junia.com',1,0,1)");
		
		$result = $sql->get_rights("maxime.herbin@student.junia.com",1);
		$this->assertEquals(0,$result["ecriture"]);
		$this->assertEquals(1,$result["lecture"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_add_file()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->add_file('C:\\\\wamp64\\\\www\\\\Unit Test',"maxime.herbin@student.junia.com","chat",3.42,"image","jpg");
		$this->assertEquals(0,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("SELECT * FROM fichier WHERE id_fichier = 1");
		$result = $query->fetch_assoc();
		
		$date = date('Y-m-d');
		
		$this->assertEquals(1,$result["id_fichier"]);
		$this->assertEquals("maxime.herbin@student.junia.com",$result["email"]);
		$this->assertEquals('C:\\\\wamp64\\\\www\\\\Unit Test',$result["source"]);
		$this->assertEquals("chat",$result["nom_fichier"]);
		$this->assertEquals($date,$result["date_publication"]);
		$this->assertEquals($date,$result["date_derniere_modification"]);
		$this->assertEquals(3.42,$result["taille_Mo"]);
		$this->assertEquals("image",$result["type"]);
		$this->assertEquals("jpg",$result["extension"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_delete_file()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->delete_file(10);
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');
		
		$img = imagecreatetruecolor(50,50);
		imagepng($img,'C:\\\\wamp64\\\\www\\\\Unit Test\\\\test.png');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')",$date,$date));
		
		$result = $sql->delete_file(1);
		$this->assertEquals(-1,$result);
		
		$query = $conn->query(sprintf("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (1,'%s')",$date));
		$result = $sql->delete_file(1);
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT * FROM fichier WHERE id_fichier = 1");
		$result = $query->fetch_assoc();
		$this->assertEquals(null,$result);
		
		$query = $conn->query("SELECT * FROM fichier_supprime WHERE id_fichier = 1");
		$result = $query->fetch_assoc();
		$this->assertEquals(null,$result);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_get_file()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->get_file(10);
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')",$date,$date));
	
		$result = $sql->get_file(1);
		
		$this->assertEquals("test",$result["nom_fichier"]);
		$this->assertEquals("maxime.herbin@student.junia.com",$result["email"]);
		$this->assertEquals($date,$result["date_publication"]);
		$this->assertEquals($date,$result["date_derniere_modification"]);
		$this->assertEquals(0.05,$result["taille_Mo"]);
		$this->assertEquals("image",$result["type"]);
		$this->assertEquals("png",$result["extension"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_modify_filename()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->modify_filename(10,"test2");
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')",$date,$date));

		$result = $sql->modify_filename(1,"test2");
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT nom_fichier FROM fichier WHERE id_fichier = 1");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("test2",$result["nom_fichier"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_add_link()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->add_link(10);
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')",$date,$date));

		$result = $sql->add_link(1);
		$this->assertEquals(-1,$result);
		
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		
		$result = $sql->add_link(1);
		$this->assertEquals(0,$result);
		
		$result = $sql->add_link(1,1);
		$this->assertEquals(-1,$result);
		
		$query = $conn->query("SELECT * FROM appartenir");
		$result = $query->fetch_assoc();
		
		$this->assertEquals(1,$result["id_fichier"]);
		$this->assertEquals(1,$result["id_tag"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_delete_link()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->delete_link(10,1);
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')",$date,$date));

		$result = $sql->delete_link(1,3);
		$this->assertEquals(-1,$result);
		
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('dorian')");
		$query = $conn->query("INSERT INTO appartenir (id_fichier,id_tag) VALUES (1,1)");
		
		$result = $sql->delete_link(1,1);
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT * FROM appartenir");
		$result = $query->fetch_assoc();
		
		$this->assertEquals(false,$result);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_get_link()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->get_link(10);
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')",$date,$date));

		$result = $sql->get_link(1);
		$this->assertEquals(true,empty($result["id_tag"]));
		
		$query = $conn->query("INSERT INTO appartenir (id_fichier,id_tag) VALUES (1,1)");
		$query = $conn->query("INSERT INTO appartenir (id_fichier,id_tag) VALUES (1,2)");
		
		$result = $sql->get_link(1);
		
		$this->assertEquals(1,$result[0]["id_tag"]);
		$this->assertEquals(2,$result[1]["id_tag"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_add_tag()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->add_tag("Dorian");
		$this->assertEquals(0,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("SELECT nom_tag FROM tag WHERE id_tag = 1");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("Dorian",$result["nom_tag"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_modify_tag_name()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->modify_tag_name(10,"Chien");
		$this->assertEquals(-1,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('Chat')");
		
		$result = $sql->modify_tag_name(1,"Chien");
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT nom_tag FROM tag WHERE id_tag = 1");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("Chien",$result["nom_tag"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_delete_tag()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->delete_tag(10);
		$this->assertEquals(-1,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("INSERT INTO tag (nom_tag) VALUES ('Chat')");
		$query = $conn->query("INSERT INTO caracteriser (id_tag,nom_categorie_tag) VALUES (1,'Animal')");
		$query = $conn->query("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES ('test',1,1,1)");
		$query = $conn->query("INSERT INTO appartenir (id_fichier,id_tag) VALUES (1,1)");
		
		$result = $sql->delete_tag(1);
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT * FROM tag");
		$result = $query->fetch_assoc();
		$this->assertEquals(false,$result);
		
		$query = $conn->query("SELECT * FROM caracteriser");
		$result = $query->fetch_assoc();
		$this->assertEquals(false,$result);
		
		$query = $conn->query("SELECT * FROM attribuer");
		$result = $query->fetch_assoc();
		$this->assertEquals(false,$result);
		
		$query = $conn->query("SELECT * FROM appartenir");
		$result = $query->fetch_assoc();
		$this->assertEquals(false,$result);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_add_tag_category()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->add_tag_category("autres");
		$this->assertEquals(0,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("SELECT * FROM categorie_tag");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("autres",$result["nom_categorie_tag"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_delete_tag_category()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->delete_tag_category("autres");
		$this->assertEquals(-1,$result);
		
		$result = $sql->delete_tag_category("test");
		$this->assertEquals(-1,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('test')");
		$query = $conn->query("INSERT INTO caracteriser (id_tag,nom_categorie_tag) VALUES (1,'test')");
		
		$result = $sql->delete_tag_category("test");
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT * FROM caracteriser");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("autres",$result["nom_categorie_tag"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_get_tag_category()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->get_tag_category();
		$this->assertEquals(-1,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('test')");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('test2')");
		
		$result = $sql->get_tag_category();
		
		$this->assertEquals("test",$result[0]["nom_categorie_tag"]);
		$this->assertEquals("test2",$result[1]["nom_categorie_tag"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_modify_tag_category_name()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->modify_tag_category_name("Chat","Chien");
		$this->assertEquals(-1,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("INSERT INTO categorie_tag (nom_categorie_tag) VALUES ('Chat')");
		$query = $conn->query("INSERT INTO caracteriser (id_tag,nom_categorie_tag) VALUES (1,'Chat')");
		
		$result = $sql->modify_tag_category_name("Chat","Chien");
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT * FROM categorie_tag");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("Chien",$result["nom_categorie_tag"]);
		
		$query = $conn->query("SELECT * FROM caracteriser");
		$result = $query->fetch_assoc();
		
		$this->assertEquals("Chien",$result["nom_categorie_tag"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_basket_file()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->basket_file(1);
		$this->assertEquals(-1,$result);
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (1,'2020-12-13')");

		$result = $sql->basket_file(1);
		$this->assertEquals(-1,$result);
		
		$query = $conn->query("DELETE FROM fichier_supprime WHERE id_fichier = 1");
		
		$date = date('Y-m-d');
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')",$date,$date));

		$result = $sql->basket_file(1);
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT * FROM fichier_supprime");
		$result = $query->fetch_assoc();
		
		$this->assertEquals(1,$result["id_fichier"]);
		$this->assertEquals($date,$result["date_suppression"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_get_basket_file()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->get_basket_file();
		$this->assertEquals(-1,$result);
		
		$result = $sql->get_basket_file("celestin.captal@student.junia.com");
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')",$date,$date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('celestin.captal@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test2',40,'%s','%s','image','png')",$date,$date));
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (1,'2020-12-13')");
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (2,'2020-12-13')");
		
		$result = $sql->get_basket_file();
		$this->assertEquals("maxime.herbin@student.junia.com",$result[0]["email"]);
		$this->assertEquals("celestin.captal@student.junia.com",$result[1]["email"]);
		
		$result = $sql->get_basket_file("maxime.herbin@student.junia.com");
		$this->assertEquals("maxime.herbin@student.junia.com",$result[0]["email"]);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_recover_file()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$result = $sql->recover_file(1);
		$this->assertEquals(-1,$result);
		
		$date = date('Y-m-d');
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')",$date,$date));

		$result = $sql->recover_file(1);
		$this->assertEquals(-1,$result);
		
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (1,'2020-12-13')");
		
		$result = $sql->recover_file(1);
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT * FROM fichier_supprime");
		$result = $query->fetch_assoc();
		$this->assertEquals(null,$result);
		
		$conn->close();
	}
	
	/** @test */
	public function Test_basket_check()
	{
		$this->clear_DB();
		$sql = new Database();
		
		$date = date('Y-m-d');
		
		$img = imagecreatetruecolor(50,50);
		imagepng($img,"C:\\\\wamp64\\\\www\\\\Unit Test\\\\test.png");
		
		$img = imagecreatetruecolor(50,50);
		imagepng($img,"C:\\\\wamp64\\\\www\\\\Unit Test\\\\test2.png");
		
		$img = imagecreatetruecolor(50,50);
		imagepng($img,"C:\\\\wamp64\\\\www\\\\Unit Test\\\\test3.png");
		
		$conn = new mysqli("localhost","root","dorian","driveTest");
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('maxime.herbin@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test',0.05,'%s','%s','image','png')",$date,$date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('celestin.captal@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test2',40,'%s','%s','image','png')",$date,$date));
		$query = $conn->query(sprintf("INSERT INTO fichier (email,source,nom_fichier,taille_Mo,date_publication,date_derniere_modification,type,extension) VALUES ('celestin.captal@student.junia.com','C:\\\\wamp64\\\\www\\\\Unit Test','test3',40,'%s','%s','image','png')",$date,$date));
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (1,'2020-12-13')");
		$query = $conn->query(sprintf("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (2,'%s')",$date));
		$query = $conn->query("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (3,'2020-12-13')");
		
		$result = $sql->basket_check();
		$this->assertEquals(0,$result);
		
		$query = $conn->query("SELECT * FROM fichier_supprime");
		$result = $query->fetch_assoc();
		$this->assertEquals(2,$result["id_fichier"]);
		
		$query = $conn->query("SELECT * FROM fichier");
		$result = $query->fetch_assoc();
		$this->assertEquals(2,$result["id_fichier"]);
		
		$conn->close();
	}

}

?>