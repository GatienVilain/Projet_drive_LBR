<?php $title = "Drive LBR"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/homepage.css\">" ?>
<?php $scripts = '<!-- <script src="public\js\homepage.js"></script> -->
                  <!-- (B) LOAD PLUPLOAD FROM CDN -->
                  <script src="https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.3/plupload.full.min.js"></script>
                  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
                  <script src="public/js/homepage.js"></script>' ?>

<?php require('public/view/banner-menu.php'); ?>

<?php ob_start(); ?>


<!-- Content -->
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

    <div id='popup-confirm-download-multipleFiles'>
		<a id='download-multipleFiles-link' href='' download ='fichiers.zip'><button class='button-confirm-download' title='Valider le téléchargement' onclick='closeConfirmationPopup()'>Confirmer</button></a>
		<button class='button-confirm-download' title='Annuler le téléchargement' onclick='closeConfirmationPopup()'>Annuler</button>
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



<div class="pagination" <?= $issorted ? "style='visibility:hidden'":'';?>>
	<button onclick='previousPage()'> Précédent </button>
	<p>Page</p>
	<p><?= $_SESSION['page']?></p>
	<button onclick='nextPage()'> Suivant </button>
</div>

<div id="show_image_popup">

  <button id="close-btn" onclick="hidePopupModal()"> </button>

  <div id="image-show-area">
  
    <img src="">
	
  </div>
  
  <div id="video-show-area">
  
    <video src="" type="" controls>
	
  </div>
  
</div>

<?php require('public/view/banner-storage.php'); ?>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>