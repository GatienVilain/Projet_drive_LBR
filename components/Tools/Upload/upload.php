<script src="https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.3/plupload.full.min.js"></script>
<?php require('image.php');?>
 
<!-- (B) LOAD PLUPLOAD FROM CDN -->

<?php

  // (A) HELPER FUNCTION - SERVER RESPONSE
  function verbose ($ok=1, $info="") 
  {

    if ($ok==0) { http_response_code(400); }
    exit(json_encode(["ok"=>$ok, "info"=>$info]));

  }

  // (B) INVALID UPLOAD
  if (empty($_FILES) || $_FILES["file"]["error"]) 
  {

    verbose(0, "Failed to move uploaded file.");

  }

  // (C) UPLOAD DESTINATION - CHANGE FOLDER IF REQUIRED!

  $extension_video = array("3gp", "3g2", "avi", "asf", "wma","wmv","flv","mkv","mka","mks","mk3d","mp4","mpg","mxf","ogg","mov","qt","ts","webm","mpeg","mp4a","mp4b","mp4r","mp4v");
  $extension_image = array("jpg","gif","png", "tif","jif", "jfif","jp2","jpx","j2k","j2c","fpx","pcd","pdf","jpeg");

  if(in_array(pathinfo($_REQUEST["name"], PATHINFO_EXTENSION), $extension_image))
  {

    $filePath = __DIR__."\..\..\..\storage".DIRECTORY_SEPARATOR."pictures";

  }

  else if(in_array(pathinfo($_REQUEST["name"], PATHINFO_EXTENSION), $extension_video))
  {

    $filePath = __DIR__."\..\..\..\storage".DIRECTORY_SEPARATOR."videos";

  }

  if (!file_exists($filePath)) 
  { 
    
    if (!mkdir($filePath, 0777, true)) 
    {

    unlink("{$filePath}.part");

    }

  }

  $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
  $fileName = preg_replace('/[^\w\._]+/', '', $fileName);
  $filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;

  // (D) DEAL WITH CHUNKS
  $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
  $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
  $out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");

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

    rename("{$filePath}.part", $filePath);
    creerMiniatureImage($filePath);

  }


  verbose(1, "Upload OK");

?>
