<?php 
require('preview.php');
require_once('../getid3/getid3.php');
?>
 
<!-- (B) LOAD PLUPLOAD FROM CDN -->

<?php
	session_start();
	// (A) HELPER FUNCTION - SERVER RESPONSE
	function verbose ($ok=1, $info="") 
	{

	if ($ok==0) { http_response_code(400); }
	exit(json_encode(["ok"=>$ok, "info"=>$info]));

	}
	
	function console_log($output, $with_script_tags = true)
	{
		$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
			');';
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

  // (B) INVALID UPLOAD
  if (empty($_FILES) || $_FILES["file"]["error"]) 
  {

    verbose(0, "Failed to move uploaded file.");

  }

  // (C) UPLOAD DESTINATION - CHANGE FOLDER IF REQUIRED!

  $extension_video = array("3gp", "3g2", "avi", "asf", "wma","wmv","flv","mkv","mka","mks","mk3d","mp4","mpg","mxf","ogg","mov","qt","ts","webm","mpeg","mp4a","mp4b","mp4r","mp4v");
  $extension_image = array("jpg","gif","png", "tif","jif", "jfif","jp2","jpx","j2k","j2c","fpx","pcd","pdf","jpeg","wbmp","avif","webp","xbm");

  $userEmail = $_SESSION["email"];
  $tmpFilePath = __DIR__."\..\..\..\storage".DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR.$userEmail;

  if(in_array(strtolower(pathinfo($_REQUEST["name"], PATHINFO_EXTENSION)), $extension_image))
  {
	$type = 'image';
    $filePath = __DIR__."\..\..\..\storage".DIRECTORY_SEPARATOR."pictures";
  }

  else if(in_array(strtolower(pathinfo($_REQUEST["name"], PATHINFO_EXTENSION)), $extension_video))
  {
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
  $fileName = preg_replace('/[^\w\._]+/', '', $fileName);
  $tmpFilePath = $tmpFilePath . DIRECTORY_SEPARATOR . $fileName;

  // (D) DEAL WITH CHUNKS
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

  // (E) CHECK IF FILE HAS BEEN UPLOADED
  if (!$chunks || $chunk == $chunks - 1) 
  {
	rename("{$tmpFilePath}.part",$tmpFilePath);
	
	$extension=pathinfo($tmpFilePath, PATHINFO_EXTENSION);
	$fileSize = round(filesize($tmpFilePath)/(float)gmp_pow(10,6),2);

	if($type == 'image')
	{
		$source ="storage\\pictures";
	}

	else
	{
		$source = "storage\\videos";
	}
	
	
	$result = add_file($source,$userEmail, pathinfo($tmpFilePath, PATHINFO_FILENAME), $fileSize, $type, $extension);
	$id = get_id($_SESSION["email"]);
	add_tag($id);
	
	$filePath=$filePath.DIRECTORY_SEPARATOR.strval($id).'.'.($extension);
	rename($tmpFilePath,$filePath);
	unlink($tmpFilePath);
	rmdir(pathinfo($tmpFilePath,PATHINFO_DIRNAME));
	
	if ($type == 'video') {
		$getid3 = new getID3();
		$duration = $getid3->analyze('..\..\..\storage\videos\\'.$id.'.'.$extension)['playtime_string'];
		
		$conn = new \mysqli("localhost", "root", "dorian", "drive");
		$query = $conn->prepare("UPDATE fichier SET duree = ? WHERE id_fichier = ?");
		$query->bind_param("si",$duration,$id);
		$query->execute();
		$conn->close();
	}
	
    creerMiniature($filePath);
  }

  verbose(1, "Upload OK");
?>
