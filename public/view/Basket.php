<?php $title = "Drive LBR"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/basket.css\">" ?>
<?php $scripts = "<script src='https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.3/plupload.full.min.js'></script>
				  <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js'></script>
				  <script src='public/js/homepage.js'></script>
				  <script src='public/js/basket.js'></script>"?>

<?php require('public/view/banner-menu.php'); ?>
<?php require_once('components/Tools/getid3/getid3.php'); ?>

<?php ob_start(); ?>

<!-- Content -->

<div class=toolbar>
	<div class=groupe1>
		<button title="Trier les fichiers par ordre alphabétique" onclick="sortFileName()">A-Z</button>
	</div>

	<div class=groupe2>
		<button title="Trier les fichiers par date de suppression" onclick="sortDeleteDate()">Date suppression</button>
	</div>
</div>


<div id='popup-options-multipleFiles'>
	<div class='header-popup' id='header-popup-options-MultipleFiles'>

		<button id='close-options-multipleFiles' class='close-button' title='Fermer' onclick="closeMultipleFiles()"><strong>←</strong></button>
		<p><strong>Options</strong></p>

	</div>

	<div class='body-popup-options'>

	<button class='buttonPopupOptions' title='Restaurer les fichiers' onclick='recoveryFiles()'>Restaurer</button></a>
	<button class='buttonPopupOptions' title='Supprimer les fichiers' onclick='deleteFiles()'>Supprimer</button>
	<p id="sizeFilesSelected">Taille : </p>
  </div>
</div>

<div class="gallery">

	<?php
	foreach ($Bfiles as $values) {
		echo $values->preview();
	}
	?>

</div>

<div id="pagination-container">
	<span class="pagination">
		<button onclick='previousPage("basketpage")'> Précédent </button>
		<p><?= $_SESSION['basketpage'] ?></p>
		<button onclick='nextPage("basketpage")'> Suivant </button>
	</span>
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