<?php $title = "Drive LBR"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/basket.css\">" ?>
<?php $scripts = "" ?>

<?php require('public/view/banner-menu.php'); ?>
<?php require_once('components/Tools/getid3/getid3.php'); ?>

<?php ob_start(); ?>

<!-- Content -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.3/plupload.full.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script src="public/js/homepage.js"></script>
<script src="public/js/basket.js"></script>

<div class = toolbar>
    <div class = groupe1>
        <button title="Trier les fichiers par ordre alphabétique" onclick = "">A-Z</button>
    </div>

    <div class = groupe2>
        <button title="Trier les fichiers par date de suppression" onclick="">Date suppression</button>
    </div>
</div>

<div id="containerGallery">

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
const files = document.querySelectorAll('.popup');
let timer;
files.forEach(file => file.addEventListener('click', event => {
  closeAllPopup();
  if(event.button == 0) {//clic gauche
	  if (event.detail === 1) {//simple clic
		  idElement = file.id + '-popup-detail';
		  if(document.getElementById(idElement).style.display != "block")
		  {
			document.getElementById(idElement).style.display = "block";  
		  }
	  }
	}
}));

files.forEach(file => file.addEventListener('contextmenu', event => {
  //clic droit
  closeAllPopup();
  idElement = file.id + '-popup-options';
  if(document.getElementById(idElement).style.display != "block")
  {
	 document.getElementById(idElement).style.display = "block";
  }
}));

</script>