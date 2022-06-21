<?php $title = "Drive LBR"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/homepage.css\">" ?>
<?php $scripts = "" ?>

<?php require('public/view/banner-menu.php'); ?>

<?php ob_start(); ?>


<!-- Content -->
<!-- <script src="public\js\homepage.js"></script> -->
<!-- (B) LOAD PLUPLOAD FROM CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.3/plupload.full.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script src="public/js/homepage.js"></script>
<div class = toolbar>

  <div class = groupe1>

    <button class="buttonHomePage" title="Trier les fichiers par ordre alphabétique" onclick = "trierNomFichier()">A-Z</button>

    <div id='popup-options-multipleFiles'>
      <div class='header-popup' id='header-popup-options-MultipleFiles'>

        <button id='close-options-multipleFiles' class='close-button' title='Fermer' onclick ="closeMultipleFiles()"><strong>←</strong></button>
        <p><strong>Options</strong></p>

      </div>

      <div class='body-popup-options'>

        <button class='buttonPopupOptions' title='Télécharger les fichiers' onclick='downloadMultipleFiles()'>Télécharger</button></a>
        <button class='buttonPopupOptions' title='Supprimer les fichiers' onclick='deleteMultipleFiles()'>Supprimer</button>
        <div id='editTag-multipleFiles'>
          <button class='buttonPopupOptions' title='Ajouter des tags' onclick='openMenuAddTagsMultipleFiles()'>+Tag(s)</button>
          <button class='buttonPopupOptions' title='Supprimer des tags' onclick='openMenuDeleteTagsMultipleFiles()'>-Tag(s)</button>
        </div>
        <p id="sizeFilesSelected">Taille : </p>
      </div>
      <?php echo($previewAddTagsMultipleFiles);
            echo($previewDeleteTagsMultipleFiles);?>

    </div>

    <div id="buttonHomePage-filters">

      <button class="buttonHomePage" title="Sélectionner des filtres" onclick = "toggleFilterMenu()">Filtres</button>
            
      <div id="popup-newCategory">

        <div class="header-popup-newTagCategory" id="header-popup-newCategory">
          <button id='close-button-newCategory' class='close-button-newTagCategory' title='Fermer' onclick ='closePopupNewCategory()'><p>←</p></button>
          <p>Nouvelle catégorie</p>
        </div>

        <div id="body-popup-newCategory">

          <input type="text" id="popup-newCategory-nameCategory" name="category" placeholder="nom catégorie">
          <button class="button-valider" id="popup-newCategory-button-valider" onclick="addNewCategory()">Valider</button>
        
        </div>

      </div>

      <div id="popup-newTag">

        <div class="header-popup-newTagCategory" id="header-popup-newTag">
          <button id='close-button-newTag' class='close-button-newTagCategory' title='Fermer' onclick ='closePopupNewTag()'><p>←</p></button>
          <p>Nouveau tag</p>
        </div>

        <div id="body-popup-newTag">

          <select id="popup-newTag-selectCategory" name="Category">
            <?php echo($previewArrayCategory)?>
          </select>
          <input type="text" id="popup-newTag-nameTag" name="tag" placeholder="nom du tag">
          <button class="button-valider"  id="popup-newTag-button-valider" onclick="addNewTag()">Valider</button>
        </div>

      </div>



      <div id="popup-filter-menu">

        <div id="filter-menu-tags">

          <div class ="filter-menu-title" id="filter-menu-tags-title"> 
                  
            <p>Tags</p>
            <div id="button-filter-menu-tags">
                    
              <button class="button-add-filter-menu-tags" id="button-filter-menu-add-category" onclick="openPopupNewCategory()" title="Créer une catégorie"><span>+catégorie</span></button>             
              <button class="button-add-filter-menu-tags" id="button-filter-menu-add-tag" onclick="openPopupNewTag()" title="Créer un tag"><span>+tag</span></button>

            </div>

          </div>

          <div class ="filter-menu-body" id="filter-menu-tags-body">
              
            <?php echo($previewTags) ?>

          </div>

        </div>

        <div id="filter-menu-extensions">

          <div class ="filter-menu-title" id="filter-menu-extensions-title"> 

            <p>Extensions</p>

            <div class="filter-menu-title-separation">

            </div>

          </div>

          <div class ="filter-menu-body" id="filter-menu-extensions-body"> 
        
            <?php echo($previewExtensions); ?>

          </div>

        </div>

        <div id="filter-menu-author">

          <div class ="filter-menu-title" id="filter-menu-author-title"> 

            <p>Auteurs</p>
            <div class="filter-menu-title-separation">
            </div>

          </div>

          <div class ="filter-menu-body" id="filter-menu-author-body"> 

            <?php echo($previewAuthors); ?>

          </div>

        </div>

        <div id="filter-menu-sort">
          <button id="button-filter-menu-sort" title="Lancer le tri" onclick='trier()'>Trier</button>
        </div>
              

      </div>
          
    </div>
          
      <input class="buttonHomePage" type="button" id="pickfiles" value="Importer" alt="Envoyer un fichier sur le serveur" onclick="openPopupUpload()";/>
      
      
  </div>

  <div class = groupe2>

    <button class="buttonHomePage" title="Trier les fichiers par date de modification" onclick="trierDateModification()">Date modification</button>
      
  </div>

</div>


<div id="containerGallery">

    <div id="popup-upload">

        <div class='header-popup' id="header-popup-upload">

            <button class='close-button' title="Fermer" onclick ="buttonClosePopupUpload()"><strong>←</strong></button>
            <p><strong>Fichier(s) importé(s)</strong></p>
        
        </div>

        <div id="body-popup-upload">
          

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

<div id="show_image_popup">

  <button id="close-btn" onclick="hidePopupModal()"> </button>

  <div id="image-show-area">
  
    <img src="">
	
  </div>
  
  <div id="video-show-area">
  
    <video src="" controls>
	
  </div>
  
</div>

<?php require('public/view/banner-storage.php'); ?>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>

    <script>
      //document.oncontextmenu = function(){return false}
    </script>

<script>
const files = document.querySelectorAll('.popup');
let timer;
files.forEach(file => file.addEventListener('click', event => {
  closeAllPopup();
  if(event.button == 0) {//clic gauche
	  if (event.detail === 1) {//simple clic
		timer = setTimeout(() => {
		  idElement = file.id + '-popup-detail';
		  if(document.getElementById(idElement).style.display != "block")
		  {
			document.getElementById(idElement).style.display = "block";  
		  }
		}, 200);
	  }
	}
}));

files.forEach(file => file.addEventListener('dblclick', event => {
  clearTimeout(timer);
	  //double clic gauche
	  if(file.tagName == 'IMG'){
		var newpath = file.getAttribute('src').substr(0,16) + file.getAttribute('src').substr(23);
		console.log(newpath);
	    openPopupModal(file.tagName,newpath);
	  }
	  else if(file.tagName == 'VIDEO'){
		openPopupModal(file.tagName,file.children[0].getAttribute('src'));
	  }
}));

files.forEach(file => file.addEventListener('contextmenu', event => {
  //clic droit
  closeAllPopup();
  let checkboxesFiles = document.getElementsByClassName('checkbox-file');
  fileChecked = false;
  for(valeur of checkboxesFiles)
    {
      if(valeur.checked)
      {
        fileChecked=true;
      }
    }
  
  if(fileChecked == false)
  {
    idElement = file.id + '-popup-options';
    if(document.getElementById(idElement).style.display != "block")
    {
	    document.getElementById(idElement).style.display = "block";
    }  
  }
  else
  {
    getFilesSelectedSize();
    idElement='popup-options-multipleFiles';
    if(document.getElementById(idElement).style.display != "inline-flex")
    {
	    document.getElementById(idElement).style.display = "inline-flex";
    }  
  }
 
}));

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
      mime_types: [{title: "Image", extensions: "jpg,gif,png, tif,jif, jfif,jp2,jpx,j2k,j2c,fpx,pcd,pdf,jpeg,wbmp,avif,webp,xbm"},{title: "Video", extensions:  "3gp, 3g2, avi, asf, wma,wmv,flv,mkv,mka,mks,mk3d,mp4,mpg,mxf,ogg,mov,qt,ts,webm,mpeg,mp4a,mp4b,mp4r,mp4v"}]
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



