<?php
class sql {
	
	private string $host = "localhost";
	private string $user = "root";
	private string $password = "dorian";
	private string $db = "drive";
	
	
function mysql_fatal_error(string $error)
{
    $output = $error;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
	return -1;
}

//ajoute un utilisateur à la table utilisateur de la base de donnée, renvoie un message d'erreur en cas d'échec
function add_user(string $email,string $prenom,string $nom,string $mdp,string $descriptif,string $role)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	$date = date("y-m-d");
	
	//on regarde si le compte n'est pas déjà dans la base de donnée
	$query = $conn->prepare("SELECT compte_supprime FROM utilisateur WHERE email = ?");
	$query->bind_param("s",$email);
	$query->execute();
	$result = $query->get_result();
	
	if($result){
		//il existe donc on regarde s'il a été supprimé
		if($result->fetch_assoc()["compte_supprime"]){
			//s'il a été supprimé, on met à jour le compte
			$query = $conn->prepare("UPDATE utilisateur SET mot_de_passe = ?,descriptif = ?,role = ?,date_inscription = ?,compte_supprime = 0 WHERE email = ?");
			$query->bind_param("sssss",$mdp,$descriptif,$date,$role,$email);
			if(!$query->execute()){
				$conn->close();
				return mysql_fatal_error("Echec de mis à jour de la base de donnée.");
			}
			$conn->close();
		}
		else {
		//s'il n'a pas été supprimé, on renvoie un message d'erreur
			$conn->close();
			return mysql_fatal_error("Le compte existe déjà.");
		}
	}
	else{
		//sinon on créé le compte
		$query = $conn->prepare("INSERT INTO utilisateur (email, prenom, nom, mot_de_passe, descriptif,role,date_inscription,compte_supprime) VALUES (?,?,?,?,?,?,?,0)");
		$query->bind_param("sssssss",$email,$prenom,$nom,$mdp,$descriptif,$role,$date);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de création d'un nouvel utilisateur.");
		}
		$conn->close();
	}
	return 0;
}

//supprime le mot de passe et la date d'inscription et passe le compte utilisateur en supprimé, renvoie un message d'erreur en cas d'échec
function delete_user(string $email)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	$query = $conn->prepare("SELECT compte_supprime FROM utilisateur WHERE email = ?");
	$query->bind_param("s",$email);
	$query->execute();
	$result = $query->get_result();
	//on regarde si le compte existe
	if($result){
		// s'il existe, on supprime le mot de passe, le rôle, la date d'inscription et on passe le compte en supprimé
		$query = $conn->prepare("UPDATE utilisateur SET mot_de_passe = NULL, role = NULL,date_inscription = NULL, compte_supprime = 1 WHERE email = ?");
		$query->bind_param("s",$email);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de suppression du compte.");
		}
		$conn->close();
	}
	else {
		//sinon le compte n'existe pas, on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("Le compte que vous voulez supprimé n'existe pas.");
	}
	return 0;
}

//renvoie les informations de l'utilisateur (Prénom, Nom, Description, Rôle, Date d'inscription, 0 si le compte n'est pas supprimé ou 1 s'il l'est)
function get_user(string $email)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	$query = $conn->prepare("SELECT prenom,nom,description,role,date_inscription,compte_supprime FROM utilisateur WHERE email = ?");
	$query->bind_param("s",$email);
	$query->execute();
	$result = $query->get_result();
	
	$conn->close();
	//on regarde si le compte existe
	if($result){
		//s'il existe, on renvoie les informations de l'utilisateur
		return $result->fetch_assoc();
	}
	else{
		//sinon le compte n'existe pas et on renvoie un message d'erreur
		return mysql_fatal_error("Le compte n'existe pas.");
	}
}

//modifie le nom et prénom de l'utilisateur, config: array(prenom => NULL,nom => NULL,mot_de_passe => NULL, description => NULL,role => NULL), NULL par défaut
function update_user($email,$options)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on modifie le prénom
	if($config['prenom'] != NULL){
		$query = $conn->prepare("UPDATE utilisateur SET prenom = ? WHERE email = ?");
		$query->bind_param("ss",$config['prenom'],$email);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de mise à jour du prénom de l'utilisateur.");
		}
	}
	
	//on modifie le nom
	if($config['nom'] != NULL){
				$query = $conn->prepare("UPDATE utilisateur SET nom = ? WHERE email = ?");
		$query->bind_param("ss",$config['nom'],$email);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de mise à jour du nom de l'utilisateur.");
		}
	}
	
	//on modifie le mot de passe
	if($config['mot_de_passe'] != NULL){
				$query = $conn->prepare("UPDATE utilisateur SET mot_de_passe = ? WHERE email = ?");
		$query->bind_param("ss",$config['mot_de_passe'],$email);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de mise à jour du mot de passe de l'utilisateur.");
		}
	}
	
	//on modifie la description
	if($config['description'] != NULL){
		$query = $conn->prepare("UPDATE utilisateur SET description = ? WHERE email = ?");
		$query->bind_param("ss",$config['description'],$email);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de mise à jour de la description de l'utilisateur.");
		}
	}
	
	//on modifie le rôle
	if($config['role'] != NULL){
		$query = $conn->prepare("UPDATE utilisateur SET role = ? WHERE email = ?");
		$query->bind_param("ss",$config['role'],$email);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de mise à jour du rôle de l'utilisateur.");
		}
	}
	$conn->close();
	return 0;
}

//ajoute le droit d'écriture (implique droit de lecture) à un utilisateur par rapport au tag associé dans la table attribuer de la base de données
function add_writing_right(string $email, string $nom_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si l'utilisateur a des droits par rapport à ce tag
	$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND nom_tag = ?");
	$query->bind_param("ss",$email,$nom_tag);
	$query->execute();
	$result = $query->get_result();
	if($result){
		//s'il en a, on regarde s'il a le droit d'écriture
		$conn->close();
		if($result->fetch_assoc()["ecriture"]){
			//si c'est le cas, on renvoie un message d'erreur
			return mysql_fatal_error("L'utilisateur a déjà le droit d'écriture sur ce tag.");
		}
		else{
			//sinon on modifie ses droits sur le tag
			return modify_rights($email,$nom_tag,1,1);
		}
	}
	else{
		//sinon on lui créé le droit d'écriture sur le tag
		$query = $conn->prepare("INSERT INTO attribuer (email,nom_tag,ecriture,lecture) VALUES (?,?,1,1)");
		$query->bind_param("ss",$email,$nom_tag);
		if($query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec d'attribution du droit d'écriture sur le tag");
		}
		$conn->close();
	}
	return 0;
}

//ajoute le droit de lecture à un utilisateur par rapport au tag associé dans la table attribuer de la base de données
function add_reading_right(string $email, string $nom_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si l'utilisateur a des droits par rapport à ce tag
	$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND nom_tag = ?");
	$query->bind_param("ss",$email,$nom_tag);
	$query->execute();
	$result = $query->get_result();
	if($result && $result->fetch_assoc()["lecture"]){
		//s'il en a, on regarde s'il a le droit de lecture
		//si c'est le cas, on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("L'utilisateur a déjà le droit de lecture sur ce tag.");
	}
	else{
		//sinon on lui créé le droit de lecture sur le tag
		$query = $conn->prepare("INSERT INTO attribuer (email,nom_tag,ecriture,lecture) VALUES (%s,%s,0,1)");
		$query->bind_param("ss",$email,$nom_tag);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec d'attribution du droit de lecture sur le tag");
		}
		$conn->close();
	}
	return 0;
}

//modifie les droit d'écriture ou de lecture à un utilisateur par rapport au tag associé dans la table attribuer de la base de données
function modify_rights(string $email, string $nom_tag, int $ecriture, int $lecture)
{
	if($ecriture == 1 && $lecture != 1){
		return mysql_fatal_error("L'attribution de droit du droit d'écriture doit impliqué le droit de lecture sur le tag.");
	}
	
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si l'utilisateur a des droits par rapport à ce tag
	$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND nom_tag = ?");
	$query->bind_param("ss",$email,$nom_tag);
	$query->execute();
	$result = $query->get_result();
	if($result && ($result->fetch_assoc()["ecriture"] != $ecriture || $result->fetch_assoc()["lecture"] != $lecture)){
		//s'il a des droits sur ce tag et qu'ils sont différents de la modification à apporter, on modifie les droits
		$query = $conn->prepare("UPDATE attribuer SET ecriture = ?, lecture = ? WHERE email = ? AND nom_tag = ?");
		$query->bind_param("iiss",$ecriture,$lecture,$email,$nom_tag);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de modification des droits sur le tag");
		}
		$conn->close();
	}
	else{
		//sinon on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("L'utilisateur n'a aucun droits sur ce tag ou aucune modification n'est nécessaire.");
	}
	return 0;
}

//supprime tous les droits d'un utilisateur par rapport au tag associé dans la table attribuer de la base de données
function delete_rights(string $email, string $nom_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si l'utilisateur a des droits par rapport à ce tag
	$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND nom_tag = ?");
	$query->bind_param("ss",$email,$nom_tag);
	$query->execute();
	$result = $query->get_result();
	if($result){
		//si c'est le cas, on les supprime
		$query = $conn->prepare("DELETE FROM attribuer WHERE email = ? AND nom_tag = ?");
		$query->bind_param("ss",$email,$nom_tag);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de suppression des droits sur le tag");
		}
		$conn->close();
	}
	else{
		//sinon on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("L'utilisateur n'a aucun droits sur ce tag");
	}
	return 0;
}

//renvoie les droits de l'utilisateur par rapport au tag associé
function get_rights(string $email, string $nom_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si l'utilisateur a des droits par rapport à ce tag
	$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND nom_tag = ?");
	$query->bind_param("ss",$email,$nom_tag);
	$query->execute();
	$result = $query->get_result();
	$conn->close();
	if($result){
		//si c'est le cas, on renvoie les droits de l'utilisateur par rapport au tag
		return $result->fetch_assoc();
	}
	else{
		//sinon on renvoie un message d'erreur
		return mysql_fatal_error("L'utilisateur n'a aucun droits sur ce tag");
	}
	return 0;
}

//ajoute un fichier, que l'utilisateur a mis, à la table fichier de la base de données
function add_file(string $source, string $nom_fichier, string $email, float $taille, string $type, string $extension)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	$date = date("y-m-d");
	
	//on regarde si le fichier existe déjà
	$query = $conn->prepare("SELECT * FROM fichier WHERE nom_fichier = ?");
	$query->bind_param("s",$nom_fichier);
	$query->execute();
	$result = $query->get_result();
	if(!$result){
		//s'il n'existe pas, on l'ajoute
		$query = $conn->prepare("INSERT INTO fichier (source,nom_fichier,email,date_de_publication,date_derniere_modification,taille(Mo),type,extension) VALUES (?,?,?,?,?,?,?,?)");
		$query->bind_param("sssssdss",$source,$nom_fichier,$email,$date,$date,$taille,$type,$extension);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec d'ajout du fichier à la base de donnée.");
		}
		$conn->close();
	}
	else{
		//sinon on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("Le fichier existe déjà.");
	}
	return 0;
}

//supprime un fichier de la table fichier et de la table fichier_supprime de la base de donnée ainsi que du serveur
function delete_file(string $nom_fichier)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si le fichier existe
	$query = $conn->prepare("SELECT source,nom_fichier,extension FROM fichier WHERE nom_fichier = ?");
	$query->bind_param("s",$nom_fichier);
	$query->execute();
	$result = $query->get_result();
	if($result){
		//s'il existe on le supprime
		$path = sprintf("%s/%s.%s",$result->fetch_assoc()["source"],$result->fetch_assoc()["source"],$result->fetch_assoc()["extension"])
		$query = $conn->prepare("DELETE FROM fichier WHERE nom_fichier = ?");
		$query->bind_param("s",$nom_fichier);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de suppression du fichierde la table fichier.");
		}
		$query = $conn->prepare("DELETE FROM fichier_supprime WHERE nom_fichier = ?");
		$query->bind_param("s",$nom_fichier);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de suppression du fichier de la table fichier supprimé.");
		}
		if(!unlink($path)){
			$conn->close();
			return mysql_fatal_error("Le fichier n'a pas pu être supprimé du serveur.");
		}
		$conn->close();
	}	
	else{
		//sinon on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("Le fichier n'a pas pu être supprimé car il n'existe pas.");
	}
	return 0;
}

//renvoie les informations associées au fichier (nom_fichier, auteur, date de publication, date de dernière modification, taille(Mo), type, extension)
function get_file(string $nom_fichier)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si le fichier existe
	$query = $conn->prepare("SELECT nom_fichier,email,date_de_publication,date_derniere_modification,taille(Mo),type,extension FROM fichier WHERE nom_fichier = ?");
	$query->bind_param("s",$nom_fichier);
	$query->execute();
	$result = $query->get_result();
	$conn->close();
	if($result){
		//s'il existe, on renvoie les informations associées au fichier
		return $result->fetch_assoc();
	}
	else{
		//le fichier n'existe pas
		return mysql_fatal_error("Le fichier n'existe pas.");
	}
}

//associe un tag à un fichier dans la table appartenir de la base de donnée
function add_link(string $nom_fichier, string $nom_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si le fichier a déjà ce tag
	$query = $conn->prepare("SELECT nom_fichier,nom_tag FROM appartenir WHERE nom_fichier = ? AND nom_tag = ?");
	$query->bind_param("ss",$nom_fichier,$nom_tag);
	$query->execute();
	$result = $query->get_result();
	if(!$result){
		//si le fichier n'a pas ce tag, on lui associe
		$query = $conn->prepare("INSERT INTO appartenir (nom_fichier,nom_tag) VALUES (?,?)");
		$query->bind_param("ss",$nom_fichier,$nom_tag);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec d'attribution du tag au fichier.");
		}
		$conn->close();
	}
	else{
		//sinon on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("Ce tag est déjà attribué au fichier.");
	}
	return 0;
}

//supprime un tag associé à un fichier dans la table appartenir de la base de donnée
function delete_link(string $nom_fichier, string $nom_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si le fichier a ce tag
	$query = $conn->prepare("SELECT * FROM appartenir WHERE nom_fichier = ? AND nom_tag = ?");
	$query->bind_param("ss",$nom_fichier,$nom_tag);
	$query->execute();
	$result = $query->get_result();
	if(!$result){
		//si le fichier a ce tag, on le supprime
		$query = $conn->prepare("DELETE FROM appartenir WHERE nom_fichier = %s AND nom_tag = %s");
		$query->bind_param("ss",$nom_fichier,$nom_tag);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de suppression du tag du fichier.");
		}
		$conn->close();
	}
	else{
		//sinon on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("Ce tag n'est pas associé à ce fichier.");
	}
	return 0;
}

//renvoie les tags associés à un fichier
function get_link($nom_fichier)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde tous les tags associés au fichier
	$query = $conn->prepare("SELECT nom_tag FROM appartenir WHERE nom_fichier = ?");
	$query->bind_param("s",$nom_fichier);
	$query->execute();
	$result = $query->get_result();
	$conn->close();
	if($result){
		return $result->fetch_assoc()["nom_tag"];
	}
	else{
		return mysql_fatal_error("Echec de récupération des tags associés au fichier.");
	}
}

//ajoute un tag associé à une catégorie de tag dans la table tag de la base de donnée
function add_tag(string $nom_tag, string $nom_categorie_tag = "autres")
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si le tag est déjà associé à la catégorie
	$query = $conn->prepare("SELECT * FROM tag WHERE nom_tag = ? AND nom_categorie_tag = ?");
	$query->bind_param("ss",$nom_tag,$nom_categorie_tag);
	$query->execute();
	$result = $query->get_result();
	if(!$result){
		//si ce n'est pas le cas, on l'associe
		$query = $conn->prepare("INSERT INTO tag(nom_tag,nom_categorie_tag) VALUES (?,?)");
		$query->bind_param("ss",$nom_tag,$nom_categorie_tag);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec d'association du tag à la catégorie.");
		}
		$conn->close();
	}
	else{
		//s'il l'est, on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("Ce tag est déjà associé à cette catégorie.");
	}
	return 0;
}

//modifie le nom d'un tag
function modify_tag_name(string $nom_tag, string $nouveau_nom_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on modifie le nom du tag dans la table tag
	$query = $conn->prepare("UPDATE tag SET nom_tag = ? WHERE nom_tag = ?");
	$query->bind_param("ss",$nouveau_nom_tag ,$nom_tag);
	if (!$query->execute()){
		$conn->close();
		return mysql_fatal_error("Echec de mise à jour du nom du tag dans la table tag.");
	}
	
	//on modifie le nom du tag dans la table appartenir
	$query = $conn->prepare("UPDATE appartenir SET nom_tag = ? WHERE nom_tag = ?");
	$query->bind_param("ss",$nouveau_nom_tag ,$nom_tag);
	if (!$query->execute()){
		$conn->close();
		return mysql_fatal_error("Echec de mise à jour du nom du tag dans la table appartenir.");
	}
	
	//on modifie le nom du tag dans la table attribuer	
	$query = $conn->prepare("UPDATE attribuer SET nom_tag = ? WHERE nom_tag = ?");
	$query->bind_param("ss",$nouveau_nom_tag ,$nom_tag);
	if (!$query->execute()){
		$conn->close();
		return mysql_fatal_error("Echec de mise à jour du nom du tag dans la table attribuer.");
	}
	
	$conn->close();
	return 0;
}

//supprime un tag
function delete_tag(string $nom_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si le tag existe
	$query = $conn->prepare("SELECT * FROM tag WHERE nom_tag = ?");
	$query->bind_param("s",$nom_tag);
	$query->execute();
	$result = $query->get_result();
	if($result){
		//s'il existe, on le suprime de la table tag et appartenir
		$query = $conn->prepare("DELETE FROM tag WHERE nom_tag = ?");
		$query->bind_param("s",$nom_tag);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de suppression du tag dans la table tag.");
		}
		$query = $conn->prepare("DELETE FROM appartenir WHERE nom_tag = ?");
		$query->bind_param("s",$nom_tag);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de suppression du tag dans la table tag.");
		}
	}
	else{
		$conn->close();
		return mysql_fatal_error("Ce tag n'existe pas.");
	}
	return 0;
}

//ajoute une catégorie de tag à la table categorie_tag de la base de donnée
function add_tag_category(string $nom_categorie_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si la catégorie existe déjà
	$query = $conn->prepare("SELECT * FROM categorie_tag WHERE nom_categorie_tag = ?");
	$query->bind_param("s",$nom_categorie_tag);
	$query->execute();
	$result = $query->get_result();
	if(!$result){
		//si la catégorie n'existe pas, on l'a créé
		$query = $conn->prepare("INSERT INTO categorie_tag (nom_categorie_tag) VALUES (?)");
		$query->bind_param("s",$nom_categorie_tag);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de création d'une catégorie de tag.");
		}
		$conn->close();
	}
	else{
		//sinon on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("La catégorie de tag existe déjà.");
	}
	return 0;
}

//supprime une catégorie de tag de la table categorie_tag de la base de donnée
function delete_tag_category(string $nom_categorie_tag)
{
	if($nom_categorie_tag == "autres"){
		return mysql_fatal_error("La catégorie 'autres' ne peut pas être supprimé.");
	}
	
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si la catégorie existe déjà
	$query = $conn->prepare("SELECT * FROM categorie_tag WHERE nom_categorie_tag = ?");
	$query->bind_param("s",$nom_categorie_tag);
	$query->execute();
	$result = $query->get_result();
	if(!$result){
		//si la catégorie existe, on la supprime et tous les tags de cette catégorie vont dans la catégorie "autres"
		$query = $conn->prepare("DELETE FROM categorie_tag WHERE nom_categorie_tag = ?");
		$query->bind_param("s",$nom_categorie_tag);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de suppression d'une catégorie de tag.");
		}
		$query = $conn->prepare("UPDATE tag SET nom_categorie_tag = 'autres' WHERE nom_categorie_tag = ?");
		$query->bind_param("s",$nom_categorie_tag);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de mise à jour de la table tag.");
		}
		$conn->close();
	}
	else{
		//sinon on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("La catégorie de tag existe déjà.");
	}
	return 0;
}

//renovie toutes les catégories de tag
function get_tag_category()
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	$query = $conn->prepare("SELECT * FROM categorie_tag");
	$query->execute();
	$result = $query->get_result();
	$conn->close();
	if($result){
		return $result->fetch_assoc()["nom_categorie_tag"];
	}
	else{
		return mysql_fatal_error("Echec de récupération des catégories de tags.");
	}
}

//modifie le nom d'une catégorie de tag
function modify_tag_category_name(string $nom_categorie_tag, string $nouveau_nom_categorie_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on modifie le nom de la catégorie de tag dans la table catégorie_tag
	$query = $conn->prepare("UPDATE categorie_tag SET nom_categorie_tag = ? WHERE nom_categorie_tag = ?");
	$query->bind_param("ss",$nouveau_nom_categorie_tag ,$nom_categorie_tag);
	if (!$query->execute()){
		$conn->close();
		return mysql_fatal_error("Echec de mise à jour du nom de la catégorie de tag dans la table categorie_tag.");
	}
	
	//on modifie le nom de la catégorie de tag dans la table tag
	$query = $conn->prepare("UPDATE tag SET nom_categorie_tag = ? WHERE nom_categorie_tag = ?");
	$query->bind_param("ss",$nouveau_nom_categorie_tag ,$nom_categorie_tag);
	if (!$query->execute()){
		$conn->close();
		return mysql_fatal_error("Echec de mise à jour du nom de la catégorie de tag dans la table tag.");
	}
	$conn->close();
	return 0;
}

//met un fichier dans la corbeille (ajoute un fichier à la table fichier_supprime)
function basket_file(string $nom_fichier)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	$date = date("y-m-d");
	
	//on regarde si le fichier est déjà dans la corbeille
	$query = $conn->prepare("SELECT * FROM fichier_supprime WHERE nom_fichier = ?");
	$query->bind_param("s",$nom_fichier);
	$query->execute();
	$result = $query->get_result();
	
	if(!$result){
		//s'il ne l'est pas, on l'ajoute à la corbeille
		$query = $conn->prepare("INSERT INTO fichier_supprime (nom_fichier,date_de_suppression) VALUES (?,?)");
		$query->bind_param("ss",$nom_fichier,$date);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de mise à la corbeille du fichier.");
		}
		$conn->close();
	}
	else{
		//sinon, on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("Le fichier est déjà dans la corbeille.");
	}
	return 0;
}

//renvoie une liste des informations de chaque fichier supprimé par l'utilisateur (si l'email de l'utilisateur n'est pas renseigné, renvoie par défaut tous les fichiers supprimés)
function get_basket_file(string $email = NULL)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	if($email == NULL){
		$query = $conn->prepare("SELECT nom_fichier,email,date_de_publication,date_derniere_modification,taille(Mo),type,extension FROM fichier AS f JOIN fichier_supprime AS fs ON f.nom_fichier = fs.nom_fichier");
		$query->execute();
		$result = $query->get_result();
		if($result){
			$conn->close();
			return $result->fetch_assoc();
		}
		else{
			$conn->close();
			return mysql_fatal_error("Erreur de récupération des fichiers supprimés");
		}
	}
	else{
		$query = $conn->prepare("SELECT nom_fichier,email,date_de_publication,date_derniere_modification,taille(Mo),type,extension FROM fichier AS f JOIN fichier_supprime AS fs ON f.nom_fichier = fs.nom_fichier WHERE email = ?");
		$query->bind_param("s",$email);
		$query->execute();
		$result = $query->get_result();
		if($result){
			$conn->close();
			return $result->fetch_assoc();
		}
		else{
			$conn->close();
			return mysql_fatal_error("Erreur de récupération des fichiers supprimés");
		}
	}
}

//restaure un fichier (supprime le fichier de la table fichier_supprime et l'ajoute à la table fichier)
function recover_file(string $nom_fichier)
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si le fichier est dans la corbeille
	$query = $conn->prepare("SELECT * FROM fichier_supprime WHERE nom_fichier = ?");
	$query->bind_param("s",$nom_fichier);
	$query->execute();
	$result = $query->get_result();
	if($result){
		//s'il est dans la corbeille, on le restaure
		$query = $conn->prepare("DELETE FROM fichier_supprime WHERE nom_fichier = ?");
		$query->bind_param("s",$nom_fichier);
		if(!$query->execute()){
			$conn->close();
			return mysql_fatal_error("Echec de restauration du fichier.");
		}
		$conn->close();
	}
	else{
		//sinon, on renvoie un message d'erreur
		$conn->close();
		return mysql_fatal_error("Le fichier n'est pas dans la corbeille.");
	}
	return 0;
}

//vérifie les fichiers de la corbeille, si la date de suppression est supérieur à 30 jours, supprime le fichier de la base de donnée et du serveur
function basket_check()
{
	//point de connexion à la base de donnée
	$conn = new mysqli($host,$user,$password,$db);
	if (!$conn){
		return mysql_fatal_error("Echec de connexion à la base de donnée.");
	}
	
	$date = date('Y-m-d', strtotime('-30 days'));
	
	$query = $conn->prepare("SELECT nom_fichier FROM fichier_supprime WHERE date_suppression < ? ");
	$query->bind_param("s",$date);
	$query->execute();
	$result = $query->get_result();
	if($result){
		$conn->close();
		$result = $result->fetch_assoc()["nom_fichier"];
		foreach($result as $value){
			delete_file($value);
		}
		unset($value);
	}
	else{
		$conn->close();
		return mysql_fatal_error("Echec de récupération des fichiers.");
	}
	return 0;
}

}
?>