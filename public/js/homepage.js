//Fonction permettant d'ouvrir et fermer le menu filtres
function toggleFilterMenu() {

	buttonFilter = document.getElementById("popup-filter-menu");

	if (buttonFilter.style.visibility == "visible") {
		buttonFilter.style.visibility = "hidden";
	}

	else {
		closeAllPopup();
		buttonFilter.style.visibility = "visible";
	}


}

//Fonction permettant d'ouvrir la popup 'nouvelle catégorie'
function openPopupNewCategory() {

	document.getElementById("popup-newCategory").style.visibility = "visible";

}

//Fonction permettant de fermer la popup options
function closeOptionsFiles() {
	document.getElementById('popup-options-multipleFiles').style.display = 'none';
	let checkboxesFiles = document.getElementsByClassName('checkbox-file');
	for (valeur of checkboxesFiles) {
		valeur.checked = false;
	}

}

//Fonction permettant d'ouvrir la popup 'nouveau tag'
function openPopupNewTag() {

	document.getElementById("popup-newTag").style.visibility = "visible";

}

//Fonction permettant de placer les fichiers sélectionnés dans la corbeille
function basketFiles() {
	if (confirm("Confirmer la suppresion des fichiers.")) {
		idFiles = ""; //le tableau//
		//On récupère tous les id des fichiers sélectionnés
		let checkboxesFiles = document.getElementsByClassName('checkbox-file');
		for (valeur of checkboxesFiles) {
			if (valeur.checked) {
				idElement = valeur.id;
				id = idElement.replace(/checkFile-/gi, '');
				idFiles = idFiles + id + " "; // Ajouter l'élément à la liste //
			}
		}
		$.ajax({
			url: 'index.php',
			data: { 'idFiles': idFiles, 'action': "basketFiles" },
			dataType: 'json',
			success: function (response) {
				if (response.status === true) {
					alert('Fichier(s) supprimé(s)');
					window.location.reload();
				}
				else alert('Suppression impossible !');
			}
		});
	}
}

//Fonction permettant de fermer le menu filtres
function closeFilterMenu() {
	document.getElementById("popup-filter-menu").style.display = "none";
}

//Fonction permettant de fermer la popup 'nouveau tag'
function closePopupNewTag() {
	document.getElementById("popup-newTag").style.visibility = "hidden";
}

//Fonction permettant de fermer la popup 'nouvelle catégorie'
function closePopupNewCategory() {
	document.getElementById("popup-newCategory").style.visibility = "hidden";
}

//Fonction permettant d'ouvrir la popup upload
function openPopupUpload() {
	document.getElementById("popup-upload").style.display = "block";
}

//Fonction permettant de fermer la popup upload sans recharger la page
function closePopupUpload() {
	document.getElementById("popup-upload").style.display = "none";
}

//Fonction permettant de fermer toutes les popups informations
function closeAllPopup() {
	let popups_detail = document.getElementsByClassName('popup-detail');
	for (valeur of popups_detail) {
		valeur.style.display = "none";
	}
}

//Fonction permettant de fermer la popup upload en rechargeant la page
function buttonClosePopupUpload() {
	document.getElementById("popup-upload").style.display = "none";
	window.location.reload();
}

//Fonction d'ouvrir la popup information sur la version mobile
function openPopupDetailMobile(idElement) {
	closeOptionsFiles();
	idPopup = idElement.replace(/button-information-/gi, "");
	idPopup += "-popup-detail"
	document.getElementById(idPopup).style.display = "block";
}

//Fonction permettant de fermer la popup informations
function closePopupDetail(idElement) {
	idElement = idElement + '-popup-detail';
	document.getElementById(idElement).style.display = "none";
}

//Fonction permettant de créer un tag
function createTag() {
	if (confirm("Confirmer la création d'un tag.")) {
		var tagName;
		var selectedCategory;
		//Catégorie à laquelle le tag sera lié
		selectedCategory = document.getElementById("popup-newTag-selectCategory").options[document.getElementById('popup-newTag-selectCategory').selectedIndex].text;
		tagName = document.getElementById("popup-newTag-nameTag").value; //Nom du tag
		$.ajax({
			url: 'index.php',
			data: { 'category': selectedCategory, 'tag': tagName, 'action': 'createTag' },
			dataType: 'json',
			success: function (response) {
				if (response.status === true) {
					alert('Tag créé');
					window.location.reload();
				}
				else alert('Création du tag impossible!');
			}
		});
	}
}

//Fonction permettant de créer une catégorie
function createCategory() {
	if (confirm("Confirmer la création d'une catégorie.")) {
		var categoryName;
		categoryName = document.getElementById("popup-newCategory-nameCategory").value;
		$.ajax({
			url: 'index.php',
			data: { 'category': categoryName, 'action': 'createCategory' },
			dataType: 'json',
			success: function (response) {
				if (response.status === true) {
					alert('Catégorie créée');
					window.location.reload();
				}
				else alert("Création de la catégorie impossible!");
			}
		});
	}
}

//Fonction permettant de classer les fichiers par ordre alphabétique ou l'inverse
function trierNomFichier() {
	$.ajax({
		url: 'index.php',
		data: { 'option': 'sortAlphabetic', 'action': 'sortMaj' },
		dataType: 'json',
		success: function (response) {
			if (response.status === true) {
				window.location.reload();
			}
			else{
				alert("Erreur!")
			}
		}
	});
}

//Fonction permettant de trier les fichiers en fonction de leur date de modification
function trierDateModification() {
	$.ajax({
		url: 'index.php',
		data: { 'option': 'sortModificationDate', 'action': 'sortMaj' },
		dataType: 'json',
		success: function (response) {
			if (response.status === true) {
				window.location.reload();
			}
			else window.location.reload();
		}
	});
}

//Fonction permettant de trier les fichiers visibles par l'utilisateur
function trier() {
	tags = ""; 
	extensions = "";
	authors = "";
	//On récupère tous les tags sélectionnés par l'utilisateur
	let checkboxesTags = document.getElementsByClassName('checkbox-filter-menu-tags');
	for (valeur of checkboxesTags) {
		if (valeur.checked) {
			idElement = valeur.id;
			idTag = idElement.replace(/filterMenu-checkTag-/gi, '');
			tags = tags + idTag + " "; // Ajouter l'élément à la liste //
		}
	}
	//On récupère toutes les extensions sélectionnées par l'utilisateur
	let checkboxesExtensions = document.getElementsByClassName('checkbox-filter-menu-extensions');
	for (valeur of checkboxesExtensions) {
		if (valeur.checked) {
			idElement = valeur.id;
			idExtension = idElement.replace(/-filterMenu-checkExtension/gi, '');
			extensions = extensions + idExtension + " "; // Ajouter l'élément à la liste //
		}
	}
	//On récupère tous les auteurs sélectionnés par l'utilisateur
	let checkboxesAuthors = document.getElementsByClassName('checkbox-filter-menu-authors');
	for (valeur of checkboxesAuthors) {
		if (valeur.checked) {
			idElement = valeur.id;
			userName = idElement.replace(/-filterMenu-checkAuthor/gi, '');
			userName = userName.replace(/_/gi, " ");
			authors = authors + userName + "/"; // Ajouter l'élément à la liste //
		}
	}
	$.ajax({
		url: 'index.php',
		data: { 'tags': tags, 'extensions': extensions, 'authors': authors, 'option': 'sortFilter', 'action': 'sortMaj' },
		dataType: 'json',
		success: function (response) {
			if (response.status === true) {
				window.location.reload();
			}
			else alert("Tri impossible!");
		}
	});
}

//Fonction permettant d'afficher ou non les éléments du menu déroulant pour les tags du menu 'filtres'
function dropdownTagsFilterMenu(idElement) {
	idElement = idElement + '-content';
	document.getElementById(idElement).classList.toggle("show");
}

//Fonction permettant d'afficher ou non les éléments du menu déroulant (add/delete tags files)
function dropdownAddDeleteTagsFiles(idElement) {
	idElement = idElement + '-content';
	element = document.getElementById(idElement)
	if (element.style.display == "inline-flex") {
		element.style.display = "none";
	}
	else {
		element.style.display = "inline-flex";
	}
}

//Fonction permettant de fermer la popup 'modification d'un tag'
function closeEditTag(idElement) {
	idTag = idElement.replace(/close-button-editTag-/gi, "");
	idPopupEditTag = "popup-editTag-" + idTag;
	document.getElementById(idPopupEditTag).style.visibility = "hidden";

}

//Fonction permettant de d'ouvrir la popup 'modification d'un tag'
function openEditTag(idElement) {
	idPopupEditTag = idElement.replace(/edit-tagName/gi, "popup-editTag");
	document.getElementById(idPopupEditTag).style.visibility = "visible";
}

//Fonction permettant d'ouvrir la popup 'modification d'une catégorie'
function openEditCategory(idElement) {
	categoryName = idElement.replace(/-edit-categoryName/gi, "");
	idPopupEditCategory = "popup-editCategory-" + categoryName;
	document.getElementById(idPopupEditCategory).style.visibility = "visible";
}

//Fonction permettant de fermer la popup 'modification d'une catégorie'
function closeEditCategory(idElement) {
	categoryName = idElement.replace(/close-button-editCategory-/gi, "");
	idPopupEditCategory = "popup-editCategory-" + categoryName;
	document.getElementById(idPopupEditCategory).style.visibility = "hidden";
}

//Fonction permettant de supprimer un tag
function deleteTag(idElement) {
	if (confirm("Confirmer la suppresion du tag.")) {
		idTag = idElement.replace(/filterMenu-deleteTag-/gi, "");
		$.ajax({
			url: 'index.php',
			data: { 'idTag': idTag, 'option': 'deleteTag', 'action': 'deleteTagOrCategory' },
			dataType: 'json',
			success: function (response) {
				if (response.status === true) {
					alert('Tag supprimé');
					window.location.reload();
				}
				else alert("Suppression impossible!");
			}
		});
	}
}

//Fonction permettant de modifier un tag
function editTag(idElement) {
	if (confirm("Confirmer la modification du tag.")) {
		idTag = idElement.replace(/editTag-button-validate-/gi, "");
		newNameTag = document.getElementById("popup-editTag-nameTag-" + idTag).value;
		idSelectedCategory = "popup-editTag-selectCategory-" + idTag;
		selectedCategory = document.getElementById(idSelectedCategory).options[document.getElementById(idSelectedCategory).selectedIndex].text
		$.ajax({
			url: 'index.php',
			data: { 'idTag': idTag, 'option': 'editTag', 'newName': newNameTag, 'category': selectedCategory, 'action': 'editTagOrCategory' },
			dataType: 'json',
			success: function (response) {
				if (response.status === true) {
					alert('Tag modifié');
					window.location.reload();
				}
				else alert('Modification impossible!');
			}
		});
	}
}

//Fonction permettant de supprimer une catégorie
function deleteCategory(idElement) {
	if (confirm("Confirmer la suppresion de la catégorie.")) {
		categoryName = idElement.replace(/-dropdown-delete/gi, "");
		$.ajax({
			url: 'index.php',
			data: { 'categoryName': categoryName, 'option': 'deleteCategory', 'action': 'deleteTagOrCategory' },
			dataType: 'json',
			success: function (response) {
				if (response.status === true) {
					alert('Catégorie supprimée');
					window.location.reload();
				}
				else {
					alert('Suppression impossible!');
				}
			}
		});
	}
}

//Fonction permettant de modifier une catégorie
function editCategory(idElement) {
	if (confirm("Confirmer la modification de la catégorie.")) {
		categoryName = idElement.replace(/editCategory-button-validate-/gi, "");
		newName = document.getElementById("popup-editCategory-nameCategory").value;//On récupère le nouveau nom de la catégorie
		$.ajax({
			url: 'index.php',
			data: { 'categoryName': categoryName, 'option': 'editCategory', 'newName': newName, 'action': 'editTagOrCategory' },
			dataType: 'json',
			success: function (response) {
				if (response.status === true) {
					alert('Catégorie modifée');
					window.location.reload();
				}
				else alert("Modification impossible!");
			}
		});
	}
}

//popup modal functions
function openPopupModal(type, path) {
	var popup = document.getElementById("show_image_popup");
	if (popup.style.display = "none") {
		popup.style.display = "flex";
	}

	if (type == 'IMG') {
		var image = document.getElementById("image-show-area");
		image.children[0].src = path;
		if (image.style.display = "none") {
			image.style.display = "flex";
		}
	}
	else if (type == 'VIDEO') {
		var video = document.getElementById("video-show-area");
		video.children[0].src = path;
		if (video.style.display = "none") {
			video.style.display = "flex";
		}
	}
}

//Fonction permettant de cacher des popups
function hidePopupModal() {
	document.getElementById("show_image_popup").style.display = "none";
	document.getElementById("image-show-area").style.display = "none";
	document.getElementById("image-show-area").children[0].src = "";
	document.getElementById("video-show-area").style.display = "none";
	document.getElementById("video-show-area").children[0].src = "";
}

//Fonction permettant d'ouvrir le menu d'ajout de tags
function openMenuAddTagsFiles() {
	document.getElementById("add-tags-multipleFiles").style.visibility = "visible";
}

//Fonction permettant d'ouvrir le menu de suppression de tags
function openMenuDeleteTagsFiles() {
	document.getElementById("delete-tags-multipleFiles").style.visibility = "visible";
}

//Fonction permettant de fermer la popup de suppresion de tags
function closeDeleteTagsFiles() {
	document.getElementById("delete-tags-multipleFiles").style.visibility = "hidden";
}

//Fonction permettant de fermer la popup d'ajout de tags
function closeAddTagsFiles() {
	document.getElementById("add-tags-multipleFiles").style.visibility = "hidden";
}

//Fonction permettant de supprimer des tags à un ou plusieurs fichiers
function deleteTagsMultipleFiles() {
	if (confirm("Confirmer la suppresion des tags.")) {
		tags = ""; //le tableau//
		idFiles = "";
		let checkboxesTags = document.getElementsByClassName('checkbox-delete-tags-multipleFiles');
		for (valeur of checkboxesTags) {
			if (valeur.checked) {
				idElement = valeur.id;
				idTag = idElement.replace(/delete-tags-multipleFiles-checkTag-/gi, '');
				tags = tags + idTag + " "; // Ajouter l'élément à la liste //
			}
		}
		let checkboxesFiles = document.getElementsByClassName('checkbox-file');
		for (valeur of checkboxesFiles) {
			if (valeur.checked) {
				idElement = valeur.id;
				id = idElement.replace(/checkFile-/gi, '');
				idFiles = idFiles + id + " "; // Ajouter l'élément à la liste //
			}
		}
		$.ajax({
			url: 'index.php',
			data: { 'tags': tags, 'action': 'deleteTagsFiles', 'files': idFiles },
			dataType: 'json',
			success: function (response) {
				if (response.status === true) {
					alert("Tag(s) supprimé(s)")
					window.location.reload();
				}
				else alert('Suppression impossible!');
			}
		});
	}
}

//Fonction permettant d'ajouter des tags à un ou plusieurs fichiers
function addTagsMultipleFiles() {
	//On demande à ce que l'utilisateur confirme son choix
	if (confirm("Confirmer l'ajout des tags.")) {
		tags = ""; //le tableau//
		idFiles = "";
		//On récupère tous les tags sélectionnés
		let checkboxesTags = document.getElementsByClassName('checkbox-add-tags-multipleFiles');
		for (valeur of checkboxesTags) {
			if (valeur.checked) {
				idElement = valeur.id;
				idTag = idElement.replace(/add-tags-multipleFiles-checkTag-/gi, '');
				tags = tags + idTag + " "; // Ajouter l'élément à la liste //
			}
		}
		let checkboxesFiles = document.getElementsByClassName('checkbox-file');
		for (valeur of checkboxesFiles) {
			if (valeur.checked) {
				idElement = valeur.id;
				id = idElement.replace(/checkFile-/gi, '');
				idFiles = idFiles + id + " "; // Ajouter l'élément à la liste //
			}
		}
		$.ajax({
			url: 'index.php',
			data: { 'tags': tags, 'action': 'addTagsFiles', 'files': idFiles },
			dataType: 'json',
			success: function (response) {
				if (response.status === true) {
					alert("Tag(s) ajouté(s)")
					window.location.reload();
				}
				else alert('Ajout impossible!');
			}
		});
	}
}

//Fonction permettant de récupérer la taille des fichiers sélectionés
function getFilesSelectedSize() {
	idFiles = "";
	let checkboxesFiles = document.getElementsByClassName('checkbox-file');
	//On récupère les id de tous les fichiers sélectionnés
	for (valeur of checkboxesFiles) {
		if (valeur.checked) {
			idElement = valeur.id;
			id = idElement.replace(/checkFile-/gi, '');
			idFiles = idFiles + id + " "; // Ajouter l'élément à la liste //
		}
	}
	$.ajax({
		url: 'index.php',
		data: { 'action': 'getFilesSize', 'files': idFiles },
		dataType: 'json',
		success: function (response) {
			if (response.status == true) {
				size = 'Taille : ' + response.size + 'Mo';
				document.getElementById('sizeFilesSelected').textContent = size;
			}
		}
	});
}

//Fonction permettant de télécharger des fichiers
function downloadFiles() {
	idFiles = "";
	//On récupère tous les fichiers qui ont été sélectionnés par l'utilisateur
	let checkboxesFiles = document.getElementsByClassName('checkbox-file');
	for (valeur of checkboxesFiles) {
		if (valeur.checked) {
			idElement = valeur.id;
			id = idElement.replace(/checkFile-/gi, '');
			idFiles = idFiles + id + " "; // Ajouter l'élément à la liste //
		}
	}
	//On fait une requête ajax pour envoyer des informations au serveur (liste des fichiers à télécharger)
	$.ajax({
		url: 'index.php',
		data: { 'action': 'downloadFiles', 'files': idFiles },
		dataType: 'json',
		success: function (response) {
			if (response.status === true) {
				//Si l'utilisateur veut télécharger plusieurs fichiers
				if (response.mode == 'multiple') {
					zipPath = response['zipPath'];
					element = document.getElementById('download-multipleFiles-link')
					element.setAttribute('href', zipPath);//On fournit le chemin du zip au client pour le téléchargement
					//On affiche la popup de confirmation
					document.getElementById('popup-confirm-download-multipleFiles').style.display = 'inline-flex';
				}
				//Si l'utilisateur veut télécharger qu'un fichier
				else if (response.mode == 'unique') {
					filePath = response['filePath'];
					fileName = response['fileName'];
					element = document.getElementById('download-multipleFiles-link')
					element.setAttribute('href', filePath);
					element.setAttribute('download', fileName); //Nom avec lequel le fichier sera téléchargé
					document.getElementById('popup-confirm-download-multipleFiles').style.display = 'inline-flex';
				}
			}
			else {
				alert("Téléchargement impossible!")
			}
		}
	});
}

//Fonction permettant de fermer la fenêtre de confirmation (en la rendant invisible)
function closeConfirmationPopup() {
	document.getElementById("popup-confirm-download-multipleFiles").style.display = 'none';
	document.getElementById("popup-options-multipleFiles").style.display = 'none';
	let checkboxesFiles = document.getElementsByClassName('checkbox-file');
	for (valeur of checkboxesFiles) {
		if (valeur.checked) {
			valeur.checked = false;
		}
	}

}

//Fonction permettant d'aller à la page précédente
function previousPage(page) {
	$.ajax({
		url: 'index.php',
		data: { 'action': 'PreviousPage', 'page': page },
		dataType: 'json',
		success: function (response) {
			if (response.status === false) {
				alert("Something went wrong")
			}
			else {
				window.location.reload();
			}
		}
	});

}

//Fonction permettant de passer à la page suivante
function nextPage(page) {
	$.ajax({
		url: 'index.php',
		data: { 'action': 'NextPage', 'page': page },
		dataType: 'json',
		success: function (response) {
			if (response.status === false) {
				alert("Something went wrong")
			}
			else {
				window.location.reload();
			}
		}

	});
}

//Fonction permettant de renommer un fichier
function renameFile(event) {
	let file = event.currentTarget;
	if (confirm("Confirmer le nouveau nom du fichier.")) {
		$.ajax({
			url: 'index.php',
			data: { 'idFile': file.name, 'new_name': file.value, 'action': "renameFile" },
			dataType: 'json'
		});
		file.placeholder = file.value;
	}
	else {
		file.value = file.placeholder;
	}
}

const files = document.querySelectorAll('.popup');
let timer;
files.forEach(file => file.addEventListener('click', event => {
	closeAllPopup();
	closeOptionsFiles();
	if (event.button == 0) {//clic gauche
		if (event.detail === 1) {//simple clic
			timer = setTimeout(() => {
				idElement = file.id + '-popup-detail';
				if (document.getElementById(idElement).style.display != "block") {
					document.getElementById(idElement).style.display = "block";
				}
			}, 300);
		}
	}
}));

//Evènement représentant le double clic gauche
files.forEach(file => file.addEventListener('dblclick', event => {
	clearTimeout(timer);
	//Si le fichier sur lequel on a cliqué est une image
	if (file.tagName == 'IMG') {
		var newpath = file.getAttribute('src').substr(0, 16) + file.getAttribute('src').substr(23);
		openPopupModal(file.tagName, newpath);
	}
	//Si le fichier sur lequel on a cliqué est une vidéo
	else if (file.tagName == 'VIDEO') {
		openPopupModal(file.tagName, file.children[0].getAttribute('src'));
	}
}));

//Evènement représentant le clic droit
files.forEach(file => file.addEventListener('contextmenu', event => {
	//clic droit
	closeAllPopup();
	//On récupère tous les objets correspondant aux checkbox des fichiers
	let checkboxesFiles = document.getElementsByClassName('checkbox-file');
	//On cherche si un fichier a été sélectionné
	fileChecked = false;
	for(valeur of checkboxesFiles)
	{
		if(valeur.checked)
		{
			fileChecked=true;
		}
	}
	//Si aucun fichier n'a été sélectionné
	if(fileChecked == false)
	{
		//On positionne le coin haut gauche de la popup au niveau du curseur
		let popup = document.getElementById('popup-options-multipleFiles');
		popup.style.top = event.y + 'px';
		popup.style.left = event.x + 'px';

		if(popup.style.display != "inline-flex")
		{
			popup.style.display = "inline-flex";
			//On coche manuellement la checkbox du fichier sur lequel l'utilisateur a cliqué
			document.getElementById('checkFile-'+file.id).checked = true;
			getFilesSelectedSize();//On appelle la fonction permettant de récupérer la taille des fichiers sélectionnés
		}
	}
	else
	{
		getFilesSelectedSize();
		//On positionne le coin haut gauche de la popup au niveau du curseur
		let popup = document.getElementById('popup-options-multipleFiles');
		popup.style.top = event.y + 'px';
		popup.style.left = event.x + 'px';
		//Si la popup n'était pas affichée, on l'affiche
		if(popup.style.display != "inline-flex")
		{
			popup.style.display = "inline-flex";
		}
	}
}));

// On initialise l'upload
window.addEventListener("load", () => {
	// On récupère l'élement qui affichera les fichiers entrain d'être upload
	var filelist = document.getElementById("body-popup-upload");
	//On initialise plupload
	var uploader = new plupload.Uploader({
		runtimes: "html5",
		browse_button: "pickfiles",
		url: "/../../components/Tools/Upload/upload.php",
		chunk_size: "2mb", //Taille des paquets envoyés au serveur (découpe du fichier original)
		filters: {
			//On filtre les fichiers que l'utilisateur peut envoyer suivant le type mime
			mime_types: [{ title: "Image", extensions: "jpg,gif,png,hdr,tif,jif, jfif,jp2,jpx,j2k,j2c,fpx,pcd,jpeg,wbmp,avif,webp,xbm" }, { title: "Video", extensions: "3gp, 3g2, avi, asf,wav,wma,wmv,flv,mkv,mka,mks,mk3d,mp4,mpg,mxf,ogg,mov,qt,ts,webm,mpeg,mp4a,mp4b,mp4r,mp4v" }]
		},
		init: {
			PostInit: () => { filelist.innerHTML = "<div id='body-popupUpload-ready'>Ready</div>"; },
			FilesAdded: (up, files) => {
				//Pour chaque fichier que l'utilisateur a sélectionné pour l'upload,
				//on affiche son nom
				plupload.each(files, (file) => {
					let row = document.createElement("div");
					row.id = file.id;
					row.innerHTML = `${file.name} (${plupload.formatSize(file.size)}) <strong></strong>`;
					filelist.appendChild(row);
				});
				uploader.start();
			},
			UploadProgress: (up, file) => {
				//On fait apparaître la progression de l'upload à coté du nom du fichier
				document.querySelector(`#${file.id} strong`).innerHTML = `${file.percent}%`;
			},
			Error: (up, err) => { console.error(err); }
		}
	});
	uploader.init();
});

const title_files = document.querySelectorAll('.title-file');

title_files.forEach(file => {
	file.addEventListener('change', renameFile, false);
});
