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

    <button class="buttonHomePage" title="Trier les fichiers par ordre alphabétique" onclick = "">A-Z</button>

    <div id="buttonHomePage-filters">

      <button class="buttonHomePage" title="Sélectionner des filtres" onclick = "toggleFilterMenu()">Filtres</button>
            
      <div id="popup-newCategory">

        <div class="header-popup-newTagCategory" id="header-popup-newCategory">
          <button id='close-button-newCategory' class='close-button-newTagCategory' title='Fermer' onclick ='closePopupNewCategory()'><strong>←</strong></button>
          <p>Nouvelle catégorie</p>
        </div>

        <div id="body-popup-newCategory">
         
        </div>

      </div>

      <div id="popup-newTag">

        <div class="header-popup-newTagCategory" id="header-popup-newTag">
          <button id='close-button-newTag' class='close-button-newTagCategory' title='Fermer' onclick ='closePopupNewTag()'><p>←</p></button>
          <p>Nouveau tag</p>
        </div>

        <div id="body-popup-newTag">

          <select name="Catégorie">
            <option value="Autre" selected>Autre</option>
            <option value="Camping">Camping</option>
            <option value="2021">2021</option>
          </select>
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
          <button id="button-filter-menu-sort" title="Lancer le tri">Trier</button>
        </div>
              

      </div>
          
    </div>
          
      <input class="buttonHomePage" type="button" id="pickfiles" value="Importer" alt="Envoyer un fichier sur le serveur" onclick="openPopupUpload()";/>
      
      
  </div>

  <div class = groupe2>

    <button class="buttonHomePage" title="Trier les fichiers par date de modification" onclick="">Date modification</button>
      
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

<?php require('public/view/banner-storage.php'); ?>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>


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



