<?php 
require('preview.php');
require_once('../getid3/getid3.php');

session_start();
//Fonction pour gérer les réponses du serveur
function verbose ($ok=1, $info="") 
{
	if ($ok==0) { http_response_code(400); }
	exit(json_encode(["ok"=>$ok, "info"=>$info]));
}

function console_log($output, $with_script_tags = true)
{
	$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .');';
	if ($with_script_tags) {
		$js_code = '<script>' . $js_code . '</script>';
	}
	//décommenter la ligne ci-dessous pour aider à débugger
	//echo $js_code;
	return -1;
}

//ajoute un fichier, que l'utilisateur a mis, à la table fichier de la base de données
function add_file(string $source, string $email, string $nom_fichier, float $taille, string $type, string $extension)
{
	//point de connexion à la base de donnée
	$conn = new \mysqli("localhost", "root", "dorian", "drive");
	if (!$conn) {
		return console_log("Echec de connexion à la base de donnée.");
	}
	$date = date("Y-m-d");
	$query = $conn->prepare("INSERT INTO fichier (source,nom_fichier,email,date_publication,date_derniere_modification,taille_Mo,type,extension) VALUES (?,?,?,?,?,?,?,?)");
	$query->bind_param("sssssdss", $source, $nom_fichier, $email, $date, $date, $taille, $type, $extension);
	if (!$query->execute()) {
		$conn->close();
		return console_log("Echec d'ajout du fichier à la base de donnée.");
	}
	$conn->close();
	return 0;
}

function add_tag(int $id_fichier){
	//point de connexion à la base de donnée
	$conn = new \mysqli("localhost", "root", "dorian", "drive");
	if (!$conn) {
		return console_log("Echec de connexion à la base de donnée.");
	}
	$query = $conn->prepare("INSERT INTO appartenir (id_tag,id_fichier) VALUES (1,?)");
	$query->bind_param("i", $id_fichier);
	if (!$query->execute()) {
		$conn->close();
		return console_log("Echec d'ajout du tag au fichier.");
	}
	$conn->close();
return 0;
}

function get_id(string $email)
{
	//point de connexion à la base de donnée
	$conn = new \mysqli("localhost", "root", "dorian", "drive");
	if (!$conn) {
		return console_log("Echec de connexion à la base de donnée.");
	}

	$query = $conn->prepare("SELECT id_fichier FROM fichier WHERE email = ? ORDER BY id_fichier DESC LIMIT 1");
	$query->bind_param("s",$email);
	$query->execute();
	$result = $query->get_result()->fetch_assoc();
	if ($result != NULL) {
		return $result["id_fichier"];
	}

	return console_log("Echec de récupération de la base de donnée.");
}

// Upload invalide
if (empty($_FILES) || $_FILES["file"]["error"]) 
{
	verbose(0, "Failed to move uploaded file.");
}
//Extensions qui sont autorisées (video et image)
$extension_video = array("3gp", "3g2", "avi", "asf", "wav","wma","wmv","flv","mkv","mka","mks","mk3d","mp4","mpg","mxf","ogg","mov","qt","ts","webm","mpeg","mp4a","mp4b","mp4r","mp4v");
$extension_image = array("jpg","gif","png", "tif","hdr","jif", "jfif","jp2","jpx","j2k","j2c","fpx","pcd","pdf","jpeg","wbmp","avif","webp","xbm");
$userEmail = $_SESSION["email"];
//on stocke les fichiers que l'utilisateur upload dans un dossier temporaire
$tmpFilePath = __DIR__."\..\..\..\storage".DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR.$userEmail;

//On regarde si le fichier est une image
if(in_array(strtolower(pathinfo($_REQUEST["name"], PATHINFO_EXTENSION)), $extension_image))
{
	//Si le fichier upload est une image, il sera stocké dans le dossier 'pictures'
	$type = 'image';
	$filePath = __DIR__."\..\..\..\storage".DIRECTORY_SEPARATOR."pictures";
}

else if(in_array(strtolower(pathinfo($_REQUEST["name"], PATHINFO_EXTENSION)), $extension_video))
{
	//Si le fichier upload est une video, il sera stocké dans le dossier 'videos'
	$type = 'video';
	$filePath = __DIR__."\..\..\..\storage".DIRECTORY_SEPARATOR."videos";
}

if (!file_exists($tmpFilePath)) 
{ 
	if (!mkdir($tmpFilePath, 0777, true)) 
	{	
		unlink("{$tmpFilePath}.part");
	}
}

$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
//On sécurise l'upload en supprimant des caractères
$fileName = preg_replace('/[^\w\._]+/', '', $fileName);
$tmpFilePath = $tmpFilePath . DIRECTORY_SEPARATOR . $fileName;

// On s'ocuppe des paquets reçus
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
$out = @fopen("{$tmpFilePath}.part", $chunk == 0 ? "wb" : "ab");
if ($out) 
{
	$in = @fopen($_FILES["file"]["tmp_name"], "rb");
	if ($in) { while ($buff = fread($in, 4096)) { fwrite($out, $buff); } }
	else { verbose(0, "Failed to open input stream"); }
	@fclose($in);
	@fclose($out);
	@unlink($_FILES["file"]["tmp_name"]);
} 

else 
{ 
	verbose(0, "Failed to open output stream"); 
}
//On vérifie que le fichier a bien été upload
if (!$chunks || $chunk == $chunks - 1) 
{
	//Si le fichier a été upload, on renome le fichier temporaire par son vrai nom
	rename("{$tmpFilePath}.part",$tmpFilePath);
	//On récupère l'extension du fichier upload
	$extension=pathinfo($tmpFilePath, PATHINFO_EXTENSION);
	//On récupèer la taille du fichier upload
	$fileSize = round(filesize($tmpFilePath)/(float)gmp_pow(10,6),2);

	//On recupère la source du fichier stocké
	if($type == 'image')
	{
		$source ="storage\\pictures";
	}

	else
	{
		$source = "storage\\videos";
	}
	//On ajoute le fichier à la base de données
	$result = add_file($source,$userEmail, pathinfo($tmpFilePath, PATHINFO_FILENAME), $fileSize, $type, $extension);
	$id = get_id($_SESSION["email"]);//On récupère l'id du fichier venant d'être ajouté
	add_tag($id);//On ajoute un tag au fichier
	$filePath=$filePath.DIRECTORY_SEPARATOR.strval($id).'.'.($extension);
	rename($tmpFilePath,$filePath);//On déplace le fichier le dossier définitif, en le renommant par son id
	unlink($tmpFilePath);//On supprime le fichier temporaire
	rmdir(pathinfo($tmpFilePath,PATHINFO_DIRNAME));//On supprime le dossier temporaire

	//Si le fichier est une vidéo
	if ($type == 'video') {
		$getid3 = new getID3();
		$duration = $getid3->analyze('..\..\..\storage\videos\\'.$id.'.'.$extension)['playtime_string'];

		$conn = new \mysqli("localhost", "root", "dorian", "drive");
		$query = $conn->prepare("UPDATE fichier SET duree = ? WHERE id_fichier = ?");
		$query->bind_param("si",$duration,$id);
		$query->execute();
		$conn->close();
	}

	//Si le fichier est une image on crée sa miniature
	if($type == 'image'){
		creerMiniature($filePath);
	}	
}
verbose(1, "Upload OK");

