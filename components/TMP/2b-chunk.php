

<!-- (A) UPLOAD BUTTON & FILE LIST -->
<input type="button" id="pickfiles" value="Upload"/>
<div id="filelist"></div>
 
<!-- (B) LOAD PLUPLOAD FROM CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.3/plupload.full.min.js"></script>
<script>
// (C) INITIALIZE UPLOADER
window.addEventListener("load", () => {
  // (C1) GET HTML FILE LIST
  var filelist = document.getElementById("filelist");
 
  // (C2) INIT PLUPLOAD
  var uploader = new plupload.Uploader({
    runtimes: "html5",
    browse_button: "pickfiles",
    url: "2b-chunk.php",
    chunk_size: '2mb',
    unique_names : true,
    filters: {
      //max_file_size: "150mb",
      mime_types: [{title: "Image", extensions: "jpg,gif,png, tif,jif, jfif,jp2,jpx,j2k,j2c,fpx,pcd,pdf,jpeg"},{title: "Video", extensions:  "3gp, 3g2, avi, asf, wma,wmv,flv,mkv,mka,mks,mk3d,mp4,mpg,mxf,ogg,mov,qt,ts,webm,mpeg,mp4a,mp4b,mp4r,mp4v"}]
    },
    init: {
      PostInit: () => { filelist.innerHTML = "<div>Ready</div>"; },
      FilesAdded: (up, files) => {
        plupload.each(files, (file) => {
          let row = document.createElement("div");
          row.id = file.id;
          row.innerHTML = `${file.name} (${plupload.formatSize(file.size)}) <strong></strong>`;
          filelist.appendChild(row);
        });
        uploader.start();
      },
      UploadProgress: (up, file) => {
        document.querySelector(`#${file.id} strong`).innerHTML = `${file.percent}%`;
      },
      Error: (up, err) => { console.error(err); }
    }
  });
  uploader.init();
});
</script>

<?php
// (A) HELPER FUNCTION - SERVER RESPONSE
function verbose ($ok=1, $info="") {
  if ($ok==0) { http_response_code(400); }
  exit(json_encode(["ok"=>$ok, "info"=>$info]));
}

// (B) INVALID UPLOAD
if (empty($_FILES) || $_FILES["file"]["error"]) {
  verbose(0, "Failed to move uploaded file.");
}

// (C) UPLOAD DESTINATION - CHANGE FOLDER IF REQUIRED!

$extension_video = array("3gp", "3g2", "avi", "asf", "wma","wmv","flv","mkv","mka","mks","mk3d","mp4","mpg","mxf","ogg","mov","qt","ts","webm","mpeg","mp4a","mp4b","mp4r","mp4v");
$extension_image = array("jpg","gif","png", "tif","jif", "jfif","jp2","jpx","j2k","j2c","fpx","pcd","pdf","jpeg");

if(in_array(pathinfo($_REQUEST["name"], PATHINFO_EXTENSION), $extension_image))
{
  $filePath = __DIR__ . DIRECTORY_SEPARATOR . "images";
}

else if(in_array(pathinfo($_REQUEST["name"], PATHINFO_EXTENSION), $extension_video))
{
  $filePath = __DIR__ . DIRECTORY_SEPARATOR . "videos";
}

if (!file_exists($filePath)) { if (!mkdir($filePath, 0777, true)) {
  verbose(0, "Failed to create $filePath");
}}

$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
$fileName = preg_replace('/[^\w\._]+/', '', $fileName);
$filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;

// (D) DEAL WITH CHUNKS
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
if ($out) {
  $in = @fopen($_FILES["file"]["tmp_name"], "rb");
  if ($in) { while ($buff = fread($in, 4096)) { fwrite($out, $buff); } }
  else { verbose(0, "Failed to open input stream"); }
  @fclose($in);
  @fclose($out);
  @unlink($_FILES["file"]["tmp_name"]);
} else { verbose(0, "Failed to open output stream"); }

// (E) CHECK IF FILE HAS BEEN UPLOADED
if (!$chunks || $chunk == $chunks - 1) { rename("{$filePath}.part", $filePath); }
//else{if(file_exists("{$filePath}.part")){unlink("{$filePath}.part");}}
verbose(1, "Upload OK");

