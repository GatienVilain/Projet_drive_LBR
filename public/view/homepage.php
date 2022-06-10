<?php $title = "Drive LBR"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/homepage.css\">" ?>
<?php $scripts = "" ?>

<?php require('public/view/banner-menu.php'); ?>

<?php ob_start(); ?>

<?php

  $fileAddedDate = "09/06/2022";
  $fileAuthor = "Dorian";
  $fileModificationDate = "09/06/2022";
  $fileName = "test";
  $fileSize = "30Go";
  $fileTag = "Sans tags";
  $fileType = ".mp4"

?>

<!-- Content -->
<!-- <script src="public\js\homepage.js"></script> -->
<!-- (B) LOAD PLUPLOAD FROM CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.3/plupload.full.min.js"></script>

<div class = toolbar>

    <div class = groupe1>

        <button class="buttonHomePage" title="Trier les fichiers par ordre alphabétique" onclick = "">A-Z</button>
        <button class="buttonHomePage" title="Sélectionner des filtres" onclick = "">Filtres</button>
        <input class="buttonHomePage" type="button" id="pickfiles" value="Importer" alt="Envoyer un fichier sur le serveur" onclick="openPopupUpload()";/>
    
    </div>

    <div class = groupe2>

        <button class="buttonHomePage" title="Trier les fichiers par date d'ajout" onclick="">Date d'ajout</button>
    
    </div>

</div>


<div id="containerGallery">

    <div id="popup-upload">

        <div class='header-popup' id="header-popup-upload">

            <button class='close-button' title="Fermer" onclick ="closePopupUpload()"><strong>←</strong></button>
            <p><strong>Fichier(s) importé(s)</strong></p>
        
        </div>

        <div id="body-popup-upload">
          

        </div>

    </div>

    <div id="popup-options">

        <div class='header-popup' id="header-popup-options">

            <button class='close-button' title="Fermer" onclick ="closePopupOptions()"><strong>←</strong></button>
            <p><strong>Options</strong></p>
        
        </div>

        <div id="body-popup-options">

          <button class="buttonPopupOptions" title="Télécharger le fichier" onclick = "">Télécharger</button>
          <button class="buttonPopupOptions" title="Supprimer le fichier" onclick = "">Supprimer</button>

        </div>

    </div>
    

    <div class="gallery">

        <?php
            foreach ($files as $values)
            {
                echo $values->preview();
            }
        ?>

    </div>

</div>

<?php require('public/view/banner-storage.php'); ?>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>

<script>

      function openPopupUpload() {
        document.getElementById("popup-upload").style.display = "block";

      }

      function closePopupUpload() {
          document.getElementById("popup-upload").style.display = "none";
        
      }

      function openPopup(event, idElement) {

        
    
        if(event.button == 0) //clic gauche
        {

          idElement = idElement + '-popup-detail';
          if(document.getElementById(idElement).style.display != "block")
          {
            closePopupOptions();
            document.getElementById(idElement).style.display = "block";  

          }

        }

        else if(event.button == 2) //clic droit
        {
          if(document.getElementById("popup-options").style.display != "block")
          {
            closePopupDetail(idElement);
            var pos_X = event.clientX;
            var pos_Y = event.clientY;
            document.getElementById("popup-options").style.display = "block";
            document.getElementById('popup-options').style.setProperty("top",pos_Y+'px');
            document.getElementById('popup-options').style.setProperty("left",pos_X+'px');

          }

        }

        
        
      }

      function closePopupDetail(idElement) {
        idElement = idElement + '-popup-detail';
        document.getElementById(idElement).style.display = "none";
      }

      function closePopupOptions() {
        document.getElementById("popup-options").style.display = "none";
      }

      function AntiClickDroitImg()
     {
      var imgs = document.getElementsByTagName('img');
      for(var i=0; i<imgs.length; i++)
       imgs[i].oncontextmenu = NeRienFaire;
     }

</script>

<script>
// (C) INITIALIZE UPLOADER
window.addEventListener("load", () => {
  // (C1) GET HTML FILE LIST
  var filelist = document.getElementById("body-popup-upload");
 
  // (C2) INIT PLUPLOAD
  var uploader = new plupload.Uploader({
    runtimes: "html5",
    browse_button: "pickfiles",
    url: "/../../components/Tools/Upload/upload.php",
    chunk_size: "2mb",
    filters: {
      //max_file_size: "150mb",
      mime_types: [{title: "Image", extensions: "jpg,gif,png, tif,jif, jfif,jp2,jpx,j2k,j2c,fpx,pcd,pdf,jpeg"},{title: "Video", extensions:  "3gp, 3g2, avi, asf, wma,wmv,flv,mkv,mka,mks,mk3d,mp4,mpg,mxf,ogg,mov,qt,ts,webm,mpeg,mp4a,mp4b,mp4r,mp4v"}]
    },
    init: {
      PostInit: () => { filelist.innerHTML = "<div id='body-popupUpload-ready'>Ready</div>"; },
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

