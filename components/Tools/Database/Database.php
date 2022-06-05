<?php
class sql {
	
	private const host = "localhost";
	private const user = "root";
	private const password = "dorian";
	private const db = "driveTest";

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
	
	//décommenter la ligne ci-dessous pour aider à débugger
    echo $js_code;
	return -1;
}

//renvoie true si l'utilisateur est dans la BDD, false sinon
function check_user(string $email):bool
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	$query = $conn->prepare("SELECT * FROM utilisateur WHERE email = ?");
	$query->bind_param("s",$email);
	$query->execute();
	$result = $query->get_result();
	
	return $result->num_rows > 0;
}

//renvoie true si le fichier est dans la BDD, false sinon
function check_file(int $id_fichier):bool
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}	
	
	$query = $conn->prepare("SELECT * FROM fichier WHERE id_fichier = ?");
	$query->bind_param("i",$id_fichier);
	$query->execute();
	$result = $query->get_result();
	
	return $result->num_rows > 0;
}

//renvoie true si le tag est dans la BDD, false sinon
function check_tag(int $id_tag):bool
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}	
	
	$query = $conn->prepare("SELECT * FROM tag WHERE id_tag = ?");
	$query->bind_param("s",$id_tag);
	$query->execute();
	$result = $query->get_result();
	
	return $result->num_rows > 0;
}

//renvoie true si la catégorie de tag est dans la BDD, false sinon
function check_tag_category(string $nom_categorie_tag):bool
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}	
	
	$query = $conn->prepare("SELECT * FROM categorie_tag WHERE nom_categorie_tag = ?");
	$query->bind_param("s",$nom_categorie_tag);
	$query->execute();
	$result = $query->get_result();
	
	return $result->num_rows > 0;
}

//ajoute un utilisateur à la table utilisateur de la base de donnée, renvoie un message d'erreur en cas d'échec
function add_user(string $email,string $prenom,string $nom,string $mdp,string $descriptif,string $role)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	$date = date("Y-m-d");
	
	//on regarde si le compte n'est pas déjà dans la base de donnée
	$query = $conn->prepare("SELECT compte_supprime FROM utilisateur WHERE email = ?");
	$query->bind_param("s",$email);
	$query->execute();
	$result = $query->get_result()->fetch_assoc();
	
	if($result != NULL){
		//il existe donc on regarde s'il a été supprimé
		if($result["compte_supprime"]){
			//s'il a été supprimé, on met à jour le compte
			$query = $conn->prepare("UPDATE utilisateur SET mot_de_passe = ?,descriptif = ?,role = ?,date_inscription = ?,compte_supprime = 0 WHERE email = ?");
			$query->bind_param("sssss",$mdp,$descriptif,$date,$role,$email);
			if(!$query->execute()){
				$conn->close();
				return $this->console_log("Echec de mis à jour de la base de donnée.");
			}
			$conn->close();
		}
		else {
		//s'il n'a pas été supprimé, on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("Le compte existe déjà.");
		}
	}
	else{
		//sinon on créé le compte
		$query = $conn->prepare("INSERT INTO utilisateur (email, prenom, nom, mot_de_passe, descriptif,role,date_inscription,compte_supprime) VALUES (?,?,?,?,?,?,?,0)");
		$query->bind_param("sssssss",$email,$prenom,$nom,$mdp,$descriptif,$role,$date);
		if(!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de création d'un nouvel utilisateur.");
		}
		$conn->close();
	}
	return 0;
}

//supprime le mot de passe et la date d'inscription et passe le compte utilisateur en supprimé, renvoie un message d'erreur en cas d'échec
function delete_user(string $email)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	//on regarde si le compte existe
	if($this->check_user($email)){
		//on regarde si ce n'est pas le dernier compte admin supprimé
		$query = $conn->prepare("SELECT COUNT(*) AS admin_restant FROM utilisateur WHERE role = 'admin' AND compte_supprime = 0");
		$query->execute();
		$result = $query->get_result()->fetch_assoc()["admin_restant"];
		if ($result == 1) {
			$conn->close();
			return $this->console_log("Echec de suppression de l'utilisateur, on ne peut pas supprimer tous les admins");
		}
		
		// si ce n'est pas le cas, on supprime le mot de passe, le rôle, la date d'inscription et on passe le compte en supprimé
		$query = $conn->prepare("UPDATE utilisateur SET mot_de_passe = null, role = null,date_inscription = null, compte_supprime = 1 WHERE email = ?");
		$query->bind_param("s",$email);
		if(!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de suppression du compte.");
		}
		$conn->close();
	}
	else {
		$conn->close();
		return $this->console_log("Le compte que vous voulez supprimé n'existe pas.");
	}
	return 0;
}

//renvoie les informations de l'utilisateur (Prénom, Nom, Description, Rôle, Date d'inscription, 0 si le compte n'est pas supprimé ou 1 s'il l'est)
function get_user(string $email)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	$query = $conn->prepare("SELECT prenom,nom,descriptif,role,date_inscription,compte_supprime FROM utilisateur WHERE email = ?");
	$query->bind_param("s",$email);
	$query->execute();
	$result = $query->get_result()->fetch_assoc();
	//on regarde si le compte existe
	if($result != NULL){
		//s'il existe, on renvoie les informations de l'utilisateur
		$conn->close();
		return $result;
	}
	else{
		return $this->console_log("Le compte n'existe pas.");
	}
}

//renvoie le mot de passe de l'utilisateur
function get_user_password(string $email)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	$query = $conn->prepare("SELECT mot_de_passe FROM utilisateur WHERE email = ?");
	$query->bind_param("s",$email);
	$query->execute();
	$result = $query->get_result()->fetch_assoc();
	//on regarde si le compte existe
	if($result != NULL){
		//s'il existe, on renvoie les informations de l'utilisateur
		$conn->close();
		return $result;
	}
	else{
		return $this->console_log("Le compte n'existe pas.");
	}
}

//modifie le nom et prénom de l'utilisateur, config: array(prenom => NULL,nom => NULL,mot_de_passe => NULL, descriptif => NULL,role => NULL), NULL par défaut
function update_user($email,$config)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	$default_config = array("prenom" => NULL,"nom" => NULL,"mot_de_passe" => NULL, "descriptif" => NULL,"role" => NULL);
	
	$configs = array_merge_recursive($default_config, $config);
	
	if ( $this->check_user($email)) {
		//on modifie le prénom et/ou
		if($configs['prenom'] != NULL){
			$query = $conn->prepare("UPDATE utilisateur SET prenom = ? WHERE email = ?");
			$query->bind_param("ss",$config['prenom'],$email);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec de mise à jour du prénom de l'utilisateur.");
			}
		}
		
		//on modifie le nom et/ou
		if($configs['nom'] != NULL){
					$query = $conn->prepare("UPDATE utilisateur SET nom = ? WHERE email = ?");
			$query->bind_param("ss",$config['nom'],$email);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec de mise à jour du nom de l'utilisateur.");
			}
		}
		
		//on modifie le mot de passe et/ou
		if($configs['mot_de_passe'] != NULL){
					$query = $conn->prepare("UPDATE utilisateur SET mot_de_passe = ? WHERE email = ?");
			$query->bind_param("ss",$config['mot_de_passe'],$email);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec de mise à jour du mot de passe de l'utilisateur.");
			}
		}
		
		//on modifie la description et/ou
		if($configs['descriptif'] != NULL){
			$query = $conn->prepare("UPDATE utilisateur SET descriptif = ? WHERE email = ?");
			$query->bind_param("ss",$config['descriptif'],$email);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec de mise à jour de la description de l'utilisateur.");
			}
		}
		
		//on modifie le rôle 
		if($configs['role'] != NULL){
			$query = $conn->prepare("UPDATE utilisateur SET role = ? WHERE email = ?");
			$query->bind_param("ss",$config['role'],$email);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec de mise à jour du rôle de l'utilisateur.");
			}
		}
	}
	else{
		$conn->close();
		return $this->console_log("Le compte n'existe pas.");
	}
	
	return 0;
}

//ajoute le droit d'écriture (implique droit de lecture) à un utilisateur par rapport au tag associé dans la table attribuer de la base de données
function add_writing_right(string $email, int $id_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if ( $this->check_user($email) && $this->check_tag($id_tag)) {
		//on regarde si l'utilisateur a des droits par rapport à ce tag
		$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND id_tag = ?");
		$query->bind_param("si",$email,$id_tag);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		if($result != NULL){
			//s'il en a, on regarde s'il a le droit d'écriture
			$conn->close();
			if($result["ecriture"]){
				//si c'est le cas, on renvoie un message d'erreur
				return $this->console_log("L'utilisateur a déjà le droit d'écriture sur ce tag.");
			}
			else{
				//sinon on modifie ses droits sur le tag
				return $this->modify_rights($email,$id_tag,1,1);
			}
		}
		else{
			//sinon on lui créé le droit d'écriture sur le tag
			$query = $conn->prepare("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES (?,?,1,1)");
			$query->bind_param("si",$email,$id_tag);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec d'attribution du droit d'écriture sur le tag");
			}
			$conn->close();
		}
	}
	else{
		$conn->close();
		return $this->console_log("Le compte ou le tag n'existe pas.");
	}
	return 0;
}

//ajoute le droit de lecture à un utilisateur par rapport au tag associé dans la table attribuer de la base de données
function add_reading_right(string $email, int $id_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if ( $this->check_user($email) && $this->check_tag($id_tag)) {
		//on regarde si l'utilisateur a des droits par rapport à ce tag
		$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND id_tag = ?");
		$query->bind_param("si",$email,$id_tag);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		if($result != NULL && $result["lecture"]){
			//s'il en a, on regarde s'il a le droit de lecture
			//si c'est le cas, on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("L'utilisateur a déjà le droit de lecture sur ce tag.");
		}
		else{
			//sinon on lui créé le droit de lecture sur le tag
			$query = $conn->prepare("INSERT INTO attribuer (email,id_tag,ecriture,lecture) VALUES (?,?,0,1)");
			$query->bind_param("si",$email,$id_tag);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec d'attribution du droit de lecture sur le tag");
			}
			$conn->close();
		}
	}
	else{
		$conn->close();
		return $this->console_log("Le compte ou le tag n'existe pas.");
	}
	return 0;
}

//modifie les droit d'écriture ou de lecture à un utilisateur par rapport au tag associé dans la table attribuer de la base de données
function modify_rights(string $email, int $id_tag, int $ecriture, int $lecture)
{
	if($ecriture == 1 && $lecture != 1){
		return $this->console_log("L'attribution de droit du droit d'écriture doit impliqué le droit de lecture sur le tag.");
	}
	
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if ( $this->check_user($email) && $this->check_tag($id_tag)) {
		//on regarde si l'utilisateur a des droits par rapport à ce tag
		$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND id_tag = ?");
		$query->bind_param("si",$email,$id_tag);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		if($result != NULL && ($result["ecriture"] != $ecriture || $result["lecture"] != $lecture)){
			//s'il a des droits sur ce tag et qu'ils sont différents de la modification à apporter, on modifie les droits
			$query = $conn->prepare("UPDATE attribuer SET ecriture = ?, lecture = ? WHERE email = ? AND id_tag = ?");
			$query->bind_param("iisi",$ecriture,$lecture,$email,$id_tag);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec de modification des droits sur le tag");
			}
			$conn->close();
		}
		else{
			//sinon on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("L'utilisateur n'a aucun droits sur ce tag ou aucune modification n'est nécessaire.");
		}
	}
	else{
		$conn->close();
		return $this->console_log("Le compte ou le tag n'existe pas.");
	}
	return 0;
}

//supprime tous les droits d'un utilisateur par rapport au tag associé dans la table attribuer de la base de données
function delete_rights(string $email, int $id_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if ( $this->check_user($email) && $this->check_tag($id_tag)) {
		//on regarde si l'utilisateur a des droits par rapport à ce tag
		$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND id_tag = ?");
		$query->bind_param("si",$email,$id_tag);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		if($result != NULL){
			//si c'est le cas, on les supprime
			$query = $conn->prepare("DELETE FROM attribuer WHERE email = ? AND id_tag = ?");
			$query->bind_param("si",$email,$id_tag);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec de suppression des droits sur le tag");
			}
			$conn->close();
		}
		else{
			//sinon on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("L'utilisateur n'a aucun droits sur ce tag");
		}
	}
	else{
		$conn->close();
		return $this->console_log("Le compte ou le tag n'existe pas.");
	}
	return 0;
}

//renvoie les droits de l'utilisateur par rapport au tag associé
function get_rights(string $email, int $id_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if ( $this->check_user($email) && $this->check_tag($id_tag)) {
		//on regarde si l'utilisateur a des droits par rapport à ce tag
		$query = $conn->prepare("SELECT ecriture,lecture FROM attribuer WHERE email = ? AND id_tag = ?");
		$query->bind_param("si",$email,$id_tag);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		if($result != NULL){
			//si c'est le cas, on renvoie les droits de l'utilisateur par rapport au tag
			$conn->close();
			return $result;
		}
		else{
			//sinon on renvoie un message d'erreur
			return $this->console_log("L'utilisateur n'a aucun droits sur ce tag");
		}
	}
	else{
		$conn->close();
		return $this->console_log("Le compte ou le tag n'existe pas.");
	}
	return 0;
}

//ajoute un fichier, que l'utilisateur a mis, à la table fichier de la base de données
function add_file(string $source, string $email, string $nom_fichier, float $taille, string $type, string $extension)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	$date = date("Y-m-d");

	$query = $conn->prepare("INSERT INTO fichier (source,nom_fichier,email,date_publication,date_derniere_modification,taille_Mo,type,extension) VALUES (?,?,?,?,?,?,?,?)");
	$query->bind_param("sssssdss",$source,$nom_fichier,$email,$date,$date,$taille,$type,$extension);
	if (!$query->execute()){
		$conn->close();
		return $this->console_log("Echec d'ajout du fichier à la base de donnée.");
	}
	$conn->close();

	return 0;
}

//supprime un fichier de la table fichier et de la table fichier_supprime de la base de donnée ainsi que du serveur
function delete_file(int $id_fichier)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si le fichier existe et qu'il est dans la table fichier supprimé
	$query = $conn->prepare("SELECT * FROM fichier as f JOIN fichier_supprime AS fs ON f.id_fichier = fs.id_fichier WHERE fs.id_fichier = ?");
	$query->bind_param("i",$id_fichier);
	$query->execute();
	$result = $query->get_result()->fetch_assoc();
	if($result != NULL){
		//s'il existe on le supprime
		$path = sprintf('%s\\%s.%s',$result["source"],$result["nom_fichier"],$result["extension"]);
		$query = $conn->prepare("DELETE FROM fichier WHERE id_fichier = ?");
		$query->bind_param("i",$id_fichier);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de suppression du fichierde la table fichier.");
		}
		$query = $conn->prepare("DELETE FROM fichier_supprime WHERE id_fichier = ?");
		$query->bind_param("i",$id_fichier);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de suppression du fichier de la table fichier supprimé.");
		}
		if(!unlink($path)){
			$conn->close();
			return $this->console_log("Le fichier n'a pas pu être supprimé du serveur.");
		}
		$conn->close();
	}	
	else{
		//sinon on renvoie un message d'erreur
		$conn->close();
		return $this->console_log("Le fichier n'a pas pu être supprimé car il n'existe pas.");
	}
	return 0;
}

//renvoie les informations associées au fichier (nom_fichier, auteur, date de publication, date de dernière modification, taille_Mo, type, extension)
function get_file(int $id_fichier)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	//on regarde si le fichier existe
	$query = $conn->prepare("SELECT nom_fichier,email,date_publication,date_derniere_modification,taille_Mo,type,extension FROM fichier WHERE id_fichier = ?");
	$query->bind_param("i",$id_fichier);
	$query->execute();
	$result = $query->get_result()->fetch_assoc();
	if($result != NULL){
		//s'il existe, on renvoie les informations associées au fichier
		$conn->close();
		return $result;
	}
	else{
		//le fichier n'existe pas
		return $this->console_log("Le fichier n'existe pas.");
	}
}

//modifier le nom d'un fichier
function modify_filename(int $id_fichier,string $nouveau_nom_fichier)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if($this->check_file($id_fichier)){
		//s'il existe, on modifie le nom associé au fichier
		$query = $conn->prepare("UPDATE fichier SET nom_fichier = ? WHERE id_fichier = ?");
		$query->bind_param("si",$nouveau_nom_fichier,$id_fichier);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de mise à jour du nom du fichier.");
		}
		$conn->close();
	}
	else{
		$conn->close();
		return $this->console_log("Le fichier n'existe pas.");
	}
	
	return 0;
}

//associe un tag à un fichier dans la table appartenir de la base de donnée
function add_link(int $id_fichier, int $id_tag = 1)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if($this->check_file($id_fichier) && $this->check_tag($id_tag)){
		//on regarde si le fichier a déjà ce tag
		$query = $conn->prepare("SELECT * FROM appartenir WHERE id_fichier = ? AND id_tag = ?");
		$query->bind_param("ii",$id_fichier,$id_tag);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		if($result == NULL){
			//si le fichier n'a pas ce tag, on lui associe
			$query = $conn->prepare("INSERT INTO appartenir (id_fichier,id_tag) VALUES (?,?)");
			$query->bind_param("ii",$id_fichier,$id_tag);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec d'attribution du tag au fichier.");
			}
			$conn->close();
		}
		else{
			//sinon on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("Ce tag est déjà attribué au fichier.");
		}
	}
	else{
		$conn->close();
		return $this->console_log("Le fichier ou le tag n'existe pas.");
	}
	return 0;
}

//supprime un tag associé à un fichier dans la table appartenir de la base de donnée
function delete_link(int $id_fichier, int $id_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if($this->check_file($id_fichier) && $this->check_tag($id_tag)) {
		//on regarde si le fichier a ce tag
		$query = $conn->prepare("SELECT * FROM appartenir WHERE id_fichier = ? AND id_tag = ?");
		$query->bind_param("ii",$id_fichier,$id_tag);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		if($result != NULL){
			//si le fichier a ce tag, on le supprime
			$query = $conn->prepare("DELETE FROM appartenir WHERE id_fichier = ? AND id_tag = ?");
			$query->bind_param("ii",$id_fichier,$id_tag);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec de suppression du tag du fichier.");
			}
			$conn->close();
		}
		else{
			//sinon on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("Ce tag n'est pas associé à ce fichier.");
		}
	}
	else{
		$conn->close();
		return $this->console_log("Le fichier ou le tag n'existe pas.");
	}
	return 0;
}

//renvoie les tags associés à un fichier
function get_link(int $id_fichier)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if ($this->check_file($id_fichier)) {
		//on regarde tous les tags associés au fichier
		$query = $conn->prepare("SELECT id_tag FROM appartenir WHERE id_fichier = ?");
		$query->bind_param("i",$id_fichier);
		$query->execute();
		$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
		$conn->close();
		if($result != NULL){
			return $result;
		}
		else{
			return $this->console_log("Echec de récupération des tags associés au fichier.");
		}
	}
	else {
		$conn->close();
		return $this->console_log("Le fichier n'existe pas.");
	}
}

//ajoute un tag  dans la table tag de la base de donnée
function add_tag(string $nom_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	$query = $conn->prepare("INSERT INTO tag (nom_tag) VALUES (?)");
	$query->bind_param("s",$nom_tag);
	if (!$query->execute()){
		$conn->close();
		return $this->console_log("Echec de création du tag.");
	}
	$conn->close();
	return 0;
}

//modifie le nom d'un tag
function modify_tag_name(int $id_tag, string $nouveau_nom_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if ($this->check_tag($id_tag)) {
		//on modifie le nom du tag dans la table tag
		$query = $conn->prepare("UPDATE tag SET nom_tag = ? WHERE id_tag = ?");
		$query->bind_param("si",$nouveau_nom_tag ,$id_tag);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de mise à jour du nom du tag.");
		}
	}
	else {
		$conn->close();
		return $this->console_log("Le tag n'existe pas.");
	}
	return 0;
}

//supprime un tag
function delete_tag(int $id_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	

	if($this->check_tag($id_tag)){
		//si le tag existe, on le suprime de la table tag, caracteriser, attribuer et appartenir
		$query = $conn->prepare("DELETE FROM tag WHERE id_tag = ?");
		$query->bind_param("i",$id_tag);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de suppression du tag de la table tag.");
		}
		
		$query = $conn->prepare("DELETE FROM caracteriser WHERE id_tag = ?");
		$query->bind_param("i",$id_tag);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de suppression du tag de la table categorie de tag.");
		}
		
		$query = $conn->prepare("DELETE FROM attribuer WHERE id_tag = ?");
		$query->bind_param("i",$id_tag);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de suppression du tag de la table attribuer.");
		}
		
		$query = $conn->prepare("DELETE FROM appartenir WHERE id_tag = ?");
		$query->bind_param("i",$id_tag);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de suppression du tag de la table appartenir.");
		}
	}
	else{
		$conn->close();
		return $this->console_log("Ce tag n'existe pas.");
	}
	return 0;
}

//ajoute une catégorie de tag à la table categorie_tag de la base de donnée
function add_tag_category(string $nom_categorie_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if(!$this->check_tag_category($nom_categorie_tag)){
		//si la catégorie n'existe pas, on la créé
		$query = $conn->prepare("INSERT INTO categorie_tag (nom_categorie_tag) VALUES (?)");
		$query->bind_param("s",$nom_categorie_tag);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de création d'une catégorie de tag.");
		}
		$conn->close();
	}
	else{
		//sinon on renvoie un message d'erreur
		$conn->close();
		return $this->console_log("La catégorie de tag existe déjà.");
	}
	return 0;
}

//supprime une catégorie de tag de la table categorie_tag de la base de donnée
function delete_tag_category(string $nom_categorie_tag)
{
	if($nom_categorie_tag == "autres"){
		return $this->console_log("La catégorie 'autres' ne peut pas être supprimé.");
	}
	
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if($this->check_tag_category($nom_categorie_tag)){
		//si la catégorie existe, on la supprime et tous les tags de cette catégorie vont dans la catégorie "autres"
		$query = $conn->prepare("DELETE FROM categorie_tag WHERE nom_categorie_tag = ?");
		$query->bind_param("s",$nom_categorie_tag);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de suppression d'une catégorie de tag.");
		}
		$query = $conn->prepare("UPDATE caracteriser SET nom_categorie_tag = 'autres' WHERE nom_categorie_tag = ?");
		$query->bind_param("s",$nom_categorie_tag);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de mise à jour de la table tag.");
		}
		$conn->close();
	}
	else{
		//sinon on renvoie un message d'erreur
		$conn->close();
		return $this->console_log("La catégorie de tag existe déjà.");
	}
	return 0;
}

//renvoie toutes les catégories de tag
function get_tag_category()
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	$query = $conn->prepare("SELECT * FROM categorie_tag");
	$query->execute();
	$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
	$conn->close();
	if($result != NULL){
		return $result;
	}
	else{
		return $this->console_log("Echec de récupération des catégories de tags.");
	}
}

//modifie le nom d'une catégorie de tag
function modify_tag_category_name(string $nom_categorie_tag, string $nouveau_nom_categorie_tag)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if ($this->check_tag_category($nom_categorie_tag)){
		//si la catégorie existe, on modifie le nom de la catégorie de tag dans la table catégorie_tag
		$query = $conn->prepare("UPDATE categorie_tag SET nom_categorie_tag = ? WHERE nom_categorie_tag = ?");
		$query->bind_param("ss",$nouveau_nom_categorie_tag ,$nom_categorie_tag);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de mise à jour du nom de la catégorie de tag dans la table categorie_tag.");
		}
		
		//on modifie le nom de la catégorie de tag dans la table caractériser
		$query = $conn->prepare("UPDATE caracteriser SET nom_categorie_tag = ? WHERE nom_categorie_tag = ?");
		$query->bind_param("ss",$nouveau_nom_categorie_tag ,$nom_categorie_tag);
		if (!$query->execute()){
			$conn->close();
			return $this->console_log("Echec de mise à jour du nom de la catégorie de tag dans la table tag.");
		}
	}
	else{
		//sinon, on renvoie un message d'erreur
		$conn->close();
		return $this->console_log("La catégorie de tag n'existe pas.");
	}
	return 0;
}

//met un fichier dans la corbeille (ajoute un fichier à la table fichier_supprime)
function basket_file(int $id_fichier)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}

	if ($this->check_file($id_fichier)) {
		$date = date("Y-m-d");
		//on regarde si le fichier est déjà dans la corbeille
		$query = $conn->prepare("SELECT * FROM fichier_supprime WHERE id_fichier = ?");
		$query->bind_param("i",$id_fichier);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		if($result == NULL){
			//s'il ne l'est pas, on l'ajoute à la corbeille
			$query = $conn->prepare("INSERT INTO fichier_supprime (id_fichier,date_suppression) VALUES (?,?)");
			$query->bind_param("is",$id_fichier,$date);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec de mise à la corbeille du fichier.");
			}
			$conn->close();
		}
		else{
			//sinon, on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("Le fichier est déjà dans la corbeille.");
		}
	}
	else {
		$conn->close();
		return $this->console_log("Le fichier n'existe pas.");
	}
	return 0;
}

//renvoie une liste des informations de chaque fichier supprimé par l'utilisateur (si l'email de l'utilisateur n'est pas renseigné, renvoie par défaut tous les fichiers supprimés)
function get_basket_file(string $email = NULL)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if($email == NULL){
		$query = $conn->prepare("SELECT fs.id_fichier,nom_fichier,email,date_publication,date_derniere_modification,taille_Mo,type,extension FROM fichier AS f JOIN fichier_supprime AS fs ON f.id_fichier = fs.id_fichier");
		$query->execute();
		$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
		$conn->close();
		if($result != NULL){
			return $result;
		}
		else{
			return $this->console_log("Erreur de récupération des fichiers supprimés");
		}
	}
	else{
		$query = $conn->prepare("SELECT fs.id_fichier,nom_fichier,email,date_publication,date_derniere_modification,taille_Mo,type,extension FROM fichier AS f JOIN fichier_supprime AS fs ON f.id_fichier = fs.id_fichier WHERE email = ?");
		$query->bind_param("s",$email);
		$query->execute();
		$result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
		$conn->close();
		if($result != NULL){
			return $result;
		}
		else{
			return $this->console_log("Erreur de récupération des fichiers supprimés");
		}
		$conn->close();
	}
}

//restaure un fichier (supprime le fichier de la table fichier_supprime et l'ajoute à la table fichier)
function recover_file(int $id_fichier)
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	if ($this->check_file($id_fichier)) {
		//on regarde si le fichier est dans la corbeille
		$query = $conn->prepare("SELECT * FROM fichier_supprime WHERE id_fichier = ?");
		$query->bind_param("i",$id_fichier);
		$query->execute();
		$result = $query->get_result()->fetch_assoc();
		if($result != NULL){
			//s'il est dans la corbeille, on le restaure
			$query = $conn->prepare("DELETE FROM fichier_supprime WHERE id_fichier = ?");
			$query->bind_param("i",$id_fichier);
			if (!$query->execute()){
				$conn->close();
				return $this->console_log("Echec de restauration du fichier.");
			}
			$conn->close();
		}
		else{
			//sinon, on renvoie un message d'erreur
			$conn->close();
			return $this->console_log("Le fichier n'est pas dans la corbeille.");
		}
	}
	else {
		$conn->close();
		return $this->console_log("Le fichier n'existe pas.");
	}
	return 0;
}

//vérifie les fichiers de la corbeille, si la date de suppression est supérieur à 30 jours, supprime le fichier de la base de donnée et du serveur
function basket_check()
{
	//point de connexion à la base de donnée
	$conn = new mysqli(self::host,self::user,self::password,self::db);
	if (!$conn){
		return $this->console_log("Echec de connexion à la base de donnée.");
	}
	
	$date = date('Y-m-d', strtotime('-30 days'));
	
	$query = $conn->prepare("SELECT id_fichier FROM fichier_supprime WHERE date_suppression < ? ");
	$query->bind_param("s",$date);
	$query->execute();
	$result = $query->get_result();
	if ($result->num_rows>0) {
		while($row_data = $result->fetch_assoc()) {
			$this->delete_file($row_data["id_fichier"]);
		}
		$result->close();
		$conn->close();
	}
	else{
		$conn->close();
		return $this->console_log("Echec de récupération des fichiers.");
	}
	
	return 0;
}

}
?>