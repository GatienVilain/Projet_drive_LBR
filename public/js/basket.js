function deleteFile(idFichier) {
	$.ajax({
		url: 'index.php',
		data: { 'idFile': idFichier, 'action': "deleteFile" },
		dataType: 'json',
		success: function (response) {
			if (response.status === true) {
				alert('File Deleted!');
				window.location.reload();
			}

			else alert('Something Went Wrong!');
		}

	});
}

function recoverFile(idFichier) {
	$.ajax({
		url: 'index.php',
		data: { 'idFile': idFichier, 'action': "recoverFile" },
		dataType: 'json',
		success: function (response) {
			if (response.status === true) {
				alert('File Recovered!');
				window.location.reload();
			}

			else alert('Something Went Wrong!');
		}

	});
}

function sortDeleteDate() {
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

function sortFileName() {
	$.ajax({
		url: 'index.php',
		data: { 'option': 'sortAlphabetic', 'action': 'sortMaj' },
		dataType: 'json',
		success: function (response) {
			console.log(response['status']);

			if (response.status === true) {
				window.location.reload();
			}

			else window.location.reload();
		}

	});
}

function deleteDefinitelyMultipleFiles() {
	if (confirm("Confirmer la suppresion des fichiers.")) {
		idFiles = ""; //le tableau//

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
			data: { 'idFiles': idFiles, 'action': "deleteDefinitelyMultipleFiles" },
			dataType: 'json',
			success: function (response) {
				if (response.status === true) {
					alert('File(s) Deleted!');
					window.location.reload();
				}

				else alert('Something Went Wrong!');
			}

		});
	}
}

function recoveryMultipleFiles() {
	if (confirm("Confirmer la restauration des fichiers.")) {
		idFiles = ""; //le tableau//

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
			data: { 'idFiles': idFiles, 'action': "recoveryMultipleFiles" },
			dataType: 'json',
			success: function (response) {
				if (response.status === true) {
					alert('Fichiers restaurés');
					window.location.reload();
				}

				else alert('Something Went Wrong!');
			}

		});
	}
}

function closeMultipleFiles() {
	document.getElementById('popup-options-multipleFiles').style.display = 'none';
}


const files = document.querySelectorAll('.popup');
let timer;
files.forEach(file => file.addEventListener('click', event => {
	closeAllPopup();
	if (event.button == 0) {//clic gauche
		if (event.detail === 1) {//simple clic
			idElement = file.id + '-popup-detail';
			if (document.getElementById(idElement).style.display != "block") {
				document.getElementById(idElement).style.display = "block";
			}
		}
	}
}));

files.forEach(file => file.addEventListener('contextmenu', event => {
	//clic droit
	closeAllPopup();
	let checkboxesFiles = document.getElementsByClassName('checkbox-file');
	fileChecked = false;
	for (valeur of checkboxesFiles) {
		if (valeur.checked) {
			fileChecked = true;
		}
	}

	if (fileChecked == false) {
		idElement = file.id + '-popup-options';
		if (document.getElementById(idElement).style.display != "block") {
			document.getElementById(idElement).style.display = "block";
		}
	}
	else {
		getFilesSelectedSize();
		idElement = 'popup-options-multipleFiles';
		if (document.getElementById(idElement).style.display != "inline-flex") {
			document.getElementById(idElement).style.display = "inline-flex";
		}
	}

}));