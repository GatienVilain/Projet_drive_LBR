function toggleFilterMenu(){

  buttonFilter = document.getElementById("popup-filter-menu");

  if(buttonFilter.style.visibility == "visible")
  {
    buttonFilter.style.visibility = "hidden";
  }
  
  else
  {
    closeAllPopup();
    buttonFilter.style.visibility = "visible";
  }


}




function openPopupNewCategory() 
{

  document.getElementById("popup-newCategory").style.visibility = "visible";

}

function closeMultipleFiles()
{
  document.getElementById('popup-options-multipleFiles').style.display='none';
}

function openPopupNewTag()
{

  document.getElementById("popup-newTag").style.visibility = "visible";
  
}

function deleteMultipleFiles()
{
  if (confirm("Confirmer la suppresion des fichiers."))
  {
    idFiles = ""; //le tableau//

    let checkboxesFiles = document.getElementsByClassName('checkbox-file');
    for(valeur of checkboxesFiles)
      {
        if(valeur.checked)
        {
          idElement = valeur.id;
          id = idElement.replace(/checkFile-/gi,'');
          idFiles=idFiles + id + " "; // Ajouter l'élément à la liste //
        }
      }

    $.ajax({
      url: 'index.php',
      data: {'idFiles' : idFiles,'action' : "deleteMultipleFiles"},
      dataType: 'json', 
      success: function (response) 
      {
        if( response.status === true )

        {
          alert('File(s) Deleted!');
          window.location.reload();
        }

        else alert('Something Went Wrong!');
      }

    });
  }
}

function closeFilterMenu()
{

  document.getElementById("popup-filter-menu").style.display = "none";

}

function closePopupNewTag()
{
  document.getElementById("popup-newTag").style.visibility = "hidden";
}


function closePopupNewCategory()
{
  document.getElementById("popup-newCategory").style.visibility = "hidden";
}

function openPopupUpload() 
{

  document.getElementById("popup-upload").style.display = "block";

}

function closePopupUpload() 
{

  document.getElementById("popup-upload").style.display = "none";

}


function closeAllPopup()
{

  let popups_options = document.getElementsByClassName('popup-options');
  for(valeur of popups_options)
  {
    valeur.style.display = "none";
  }


  let popups_detail = document.getElementsByClassName('popup-detail');
  for(valeur of popups_detail)
  {
    valeur.style.display = "none";
  }
}

function buttonClosePopupUpload() 
{

  document.getElementById("popup-upload").style.display = "none";
  window.location.reload(); 

}

function openPopupDetailMobile(idElement)
{
  idPopup = idElement.replace(/button-information-/gi,"");
  idPopup += "-popup-detail"
  document.getElementById(idPopup).style.display="block";
}

function openPopup(event, idElement) 
{

  closeAllPopup();
  if(event.button == 0) //clic gauche
  {


    idElement = idElement + '-popup-detail';

    if(document.getElementById(idElement).style.display != "block")
    {

    document.getElementById(idElement).style.display = "block";  

    }

  }

  else if(event.button == 2) //clic droit
  {



    idElement = idElement + '-popup-options';

    if(document.getElementById(idElement).style.display != "block")
    {

      document.getElementById(idElement).style.display = "block";

    }

  }



}

function closePopupDetail(idElement) 
{

  idElement = idElement + '-popup-detail';
  document.getElementById(idElement).style.display = "none";

}

function closePopupOptions(idElement) 
{

  idElement = idElement + '-popup-options';
  document.getElementById(idElement).style.display = "none";

}

function basketFile(idFichier)
{
  if (confirm("Confirmer la suppresion du fichier."))
  {
    //var file_path = "storage/pictures/58.png";
    $.ajax({
    url: 'index.php',
    data: {'idFile' : idFichier,'action' : "basketFile"},
    dataType: 'json', 
    success: function (response) 
    {
      if( response.status === true )

      {
        alert('File Deleted!');
        window.location.reload();
      }

      else alert('Something Went Wrong!');
    }

    });
  }
  
}

function addNewTag()
{
  if (confirm("Confirmer l'ajout d'un tag."))
  {

    var tagName;
    var selectedCategory;
    selectedCategory = document.getElementById("popup-newTag-selectCategory").options[document.getElementById('popup-newTag-selectCategory').selectedIndex].text;
    tagName = document.getElementById("popup-newTag-nameTag").value;
    //var file_path = "storage/pictures/58.png";
    $.ajax({
      url: 'index.php',
      data: {'category' : selectedCategory,'tag' : tagName,'action' : 'addNewTag'},
      dataType: 'json', 
      success: function (response) 
      {
        if( response.status === true )

        {
          alert('Tag ajouté');
          window.location.reload();
        }

        else alert('Something Went Wrong!');
      }

    });
  }
}

function addNewCategory()
{
  if (confirm("Confirmer l'ajout d'une catégorie."))
  {
    var categoryName;
    categoryName = document.getElementById("popup-newCategory-nameCategory").value;
    $.ajax({
      url: 'index.php',
      data: {'category' : categoryName,'action' : 'addNewCategory'},
      dataType: 'json', 
      success: function (response) 
      {
        if( response.status === true )

        {
          alert('Catégorie ajoutée');
          window.location.reload();
        }

        else alert(response);
      }

    });
  }
}

function trierNomFichier()
{
  $.ajax({
    url: 'index.php',
    data: {'option' : 'sortAlphabetic','action' : 'sortMaj'},
    dataType: 'json', 
    success: function (response) 
    {
      console.log(response['status']);

      if( response.status === true )

      {
        window.location.reload();
      }

      else window.location.reload();
    }

  });
}

function trierDateModification()
{
  $.ajax({
    url: 'index.php',
    data: {'option' : 'sortModificationDate','action' : 'sortMaj'},
    dataType: 'json', 
    success: function (response) 
    {
      if( response.status === true )

      {
        window.location.reload();
      }

      else window.location.reload();
    }

  });
}

function trier()
{

  tags = ""; //le tableau//
  extensions = "";
  authors = "";
  //var checkboxesTags = document.getElementsByClassName("checkbox-filter-menu-tags");
  //var checkboxesExtensions = document.getElementsByClassName("checkbox-filter-menu-extensions");
  //var checkboxesAuthors = document.getElementsByClassName("checkbox-filter-menu-authors");
  // ici il faut mettre un élément commun à tout les chekboxe, afin de//
  // pouvoir agir sur chacun de ceux-ci, donc, une classe//

  let checkboxesTags = document.getElementsByClassName('checkbox-filter-menu-tags');
  for(valeur of checkboxesTags)
    {
      if(valeur.checked)
      {
        idElement = valeur.id;
        idTag = idElement.replace(/filterMenu-checkTag-/gi,'');
        tags=tags + idTag + " "; // Ajouter l'élément à la liste //
      }
    }

    //console.log(tags);
  
  let checkboxesExtensions = document.getElementsByClassName('checkbox-filter-menu-extensions');
  for(valeur of checkboxesExtensions)
    {
      if(valeur.checked)
      {
        idElement = valeur.id;
        idExtension = idElement.replace(/-filterMenu-checkExtension/gi,'');
        extensions=extensions + idExtension + " "; // Ajouter l'élément à la liste //
      }
    }

  let checkboxesAuthors = document.getElementsByClassName('checkbox-filter-menu-authors');
  for(valeur of checkboxesAuthors)
    {
      if(valeur.checked)
      {
        idElement = valeur.id;
        userName = idElement.replace(/-filterMenu-checkAuthor/gi,'');
        userName=userName.replace(/_/gi," ");
        authors=authors + userName + "/"; // Ajouter l'élément à la liste //
      }
    }
  //console.log(authors);
    //console.log(extensions);
  $.ajax({
    url: 'index.php',
    data: {'tags' : tags,'extensions' : extensions,'authors':authors,'option':'sortFilter','action' : 'sortMaj'},
    dataType: 'json', 
    success: function (response) 
    {
      //console.log(response["status"]);
      if( response.status === true )

      {
        window.location.reload();
      }

      else alert(response);
    }

  });

}


function myFunction(idElement) 
{
  idElement=idElement+'-content';
  console.log(idElement);
  document.getElementById(idElement).classList.toggle("show");

}

function myFunctionBis(idElement) 
{
  idElement=idElement+'-content';
  console.log(idElement)
  document.getElementById("genre-dropdown-addDelete-tags-content").style.display="block";

}


function openPopupEditTag(idButton)
{

  idTag = idButton.replace('edit-tagName-','');

  var divPopupEditTag = document.createElement('div');
  divPopupEditTag.setAttribute('id','popup-editTag');
  //divPopupEditTag.setAttribute('class','');

  var divHeaderPopupEditTag = document.createElement('div');
  divHeaderPopupEditTag.setAttribute('id','header-popup-editTag');
  divHeaderPopupEditTag.setAttribute('class','header-popup-editTagCategory');

  var divBodyPopupEditTag = document.createElement('div');
  divBodyPopupEditTag.setAttribute('id','body-popup-editTag');
  divBodyPopupEditTag.setAttribute('class','body-popup-editTagCategory');

  var divContainerButtonsEditTag = document.createElement('div');
  divContainerButtonsEditTag.setAttribute('id','container-buttons-editTag');
  divContainerButtonsEditTag.setAttribute('class','container-buttons-editTagCategory');
  
  
  var buttonCancelEditTag = document.createElement('button');
  buttonCancelEditTag.setAttribute('id','button-cancel-editTag');
  buttonCancelEditTag.setAttribute('class','button-cancel-editTagCategory');
  buttonCancelEditTag.setAttribute('title','Annuler la modification');
  //buttonCancelEditTag.setAttribute('onclick','');

  var buttonValidateEditTag = document.createElement('button');
  buttonValidateEditTag.setAttribute('id','editTag-button-validate-'+idTag);
  buttonValidateEditTag.setAttribute('class','button-validate-editTagCategory');
  buttonValidateEditTag.setAttribute('title','Valider la modification');
  //buttonValidateEditTag.setAttribute('onclick','');

  var selectNewCategoryEditTag = document.createElement('select');
  selectNewCategoryEditTag.setAttribute('id','popup-editTag-selectCategory');
  selectNewCategoryEditTag.setAttribute('name','category');

  var inputNewTagName = document.createElement('input');
  inputNewTagName.setAttribute('id','popup-editTag-nameTag');
  inputNewTagName.setAttribute('class','');
  inputNewTagName.setAttribute('type','text');
  inputNewTagName.setAttribute('name','tag');
  inputNewTagName.setAttribute('placeholder','Nouveau nom');

}

function closeEditTag(idElement)
{
  idTag = idElement.replace(/close-button-editTag-/gi,"");
  idPopupEditTag = "popup-editTag-" + idTag;
  document.getElementById(idPopupEditTag).style.visibility ="hidden";

}

function closePopupAddTag(idElement)
{
  idTag = idElement.replace(/close-button-addTag-/gi,"");
  idPopupAddTag = "add-tags-file-" + idTag;
  document.getElementById(idPopupAddTag).style.visibility ="hidden";
}

function closePopupDeleteTag(idElement)
{
  idTag = idElement.replace(/close-button-deleteTag-/gi,"");
  idPopupAddTag = "delete-tags-file-" + idTag;
  document.getElementById(idPopupAddTag).style.visibility ="hidden";
}

function openEditTag(idElement)
{

  idPopupEditTag = idElement.replace(/edit-tagName/gi,"popup-editTag");
  //idPopupEditTag = "popup-editTag-" + idTag;
  document.getElementById(idPopupEditTag).style.visibility = "visible";

}

function openEditCategory(idElement)
{

  categoryName = idElement.replace(/-edit-categoryName/gi,"");
  idPopupEditCategory = "popup-editCategory-" + categoryName;
  document.getElementById(idPopupEditCategory).style.visibility = "visible";

}

function closeEditCategory(idElement)
{

  categoryName = idElement.replace(/close-button-editCategory-/gi,"");
  idPopupEditCategory = "popup-editCategory-" + categoryName;
  document.getElementById(idPopupEditCategory).style.visibility ="hidden";

}

function deleteTag(idElement)
{
  if (confirm("Confirmer la suppresion du tag."))
  {

    idTag = idElement.replace(/filterMenu-deleteTag-/gi,"");
    $.ajax({
      url: 'index.php',
      data: {'idTag' : idTag,'option' : 'deleteTag','action' : 'deleteTagOrCategory'},
      dataType: 'json', 
      success: function (response) 
      {
        if( response.status === true )

        {
          alert('Tag supprimé');
          window.location.reload();
        }

        else alert("Erreur");
      }

    });
  }

}

function editTag(idElement)
{
  if (confirm("Confirmer la modification du tag."))
  {
    idTag = idElement.replace(/editTag-button-validate-/gi,"");
    newNameTag = document.getElementById("popup-editTag-nameTag-"+idTag).value;
    console.log(newNameTag);
    console.log(idTag);
    idSelectedCategory = "popup-editTag-selectCategory-"+idTag;
    selectedCategory = document.getElementById(idSelectedCategory).options[document.getElementById(idSelectedCategory).selectedIndex].text
    console.log(selectedCategory);
    $.ajax({
      url: 'index.php',
      data: {'idTag' : idTag,'option' : 'editTag','newName' : newNameTag,'category':selectedCategory,'action' : 'editTagOrCategory'},
      dataType: 'json', 
      success: function (response) 
      {
        if( response.status === true )

        {
          alert('Tag modifié');
          window.location.reload();
        }

        else alert('Erreur');
      }

    });
  }

}

function deleteCategory(idElement)
{
  if (confirm("Confirmer la suppresion de la catégorie."))
  {

    categoryName = idElement.replace(/-dropdown-delete/gi,"");
    $.ajax({
      url: 'index.php',
      data: {'categoryName' : categoryName,'option' : 'deleteCategory','action' : 'deleteTagOrCategory'},
      dataType: 'json', 
      success: function (response) 
      {
        console.log(response['status']);
        if( response.status === true )

        {
          alert('Catégorie supprimée');
          window.location.reload();
        }

        else {
          console.log('Erreur');
        }
        
        //window.location.reload();
      }

    });
  }

}

function editCategory(idElement)
{
  if (confirm("Confirmer la modification de la catégorie."))
  {

    categoryName = idElement.replace(/editCategory-button-validate-/gi,"");
    newName = document.getElementById("popup-editCategory-nameCategory").value;
    console.log(newName);
    console.log(categoryName);
    $.ajax({
      url: 'index.php',
      data: {'categoryName' : categoryName,'option' : 'editCategory','newName' : newName,'action' : 'editTagOrCategory'},
      dataType: 'json', 
      success: function (response) 
      {
        alert(response);
        if( response.status === true )

        {
          alert('Catégorie modifée');
          window.location.reload();
        }

        else alert("Erreur");
      }

    });
  }

}


//popup modal functions
function openPopupModal(type,path){
  var popup = document.getElementById("show_image_popup");
  if (popup.style.display = "none"){
  popup.style.display = "flex";
  }
  
  if(type == 'IMG'){
    var image = document.getElementById("image-show-area");
    image.children[0].src = path;
    if (image.style.display = "none"){
    image.style.display = "flex";
    }
  }
  else if(type == 'VIDEO'){
    var video = document.getElementById("video-show-area");
    video.children[0].src = path;
    if (video.style.display = "none"){
    video.style.display = "flex";
    }
  }
}

function hidePopupModal(){
  document.getElementById("show_image_popup").style.display = "none";
  document.getElementById("image-show-area").style.display = "none";
  document.getElementById("image-show-area").children[0].src = "";
  document.getElementById("video-show-area").style.display = "none";
  document.getElementById("video-show-area").children[0].src = "";
}


function openAddTagsToFile(idElement)
{
  idMenu = idElement.replace(/add-tags-toFile-/gi,"add-tags-file-");
  console.log(idMenu);
  document.getElementById(idMenu).style.visibility = 'visible'; 


}

function openDeleteTagsToFile(idElement)
{
  idMenu = idElement.replace(/delete-tags-toFile-/gi,"delete-tags-file-");
  console.log(idMenu);
  document.getElementById(idMenu).style.visibility = 'visible';   
}


function deleteTagsFile(elementId)
{
  if (confirm("Confirmer la suppression des tags."))
  {
    tags = ""; //le tableau//

    let checkboxesTags = document.getElementsByClassName('checkbox-delete-tags');
    for(valeur of checkboxesTags)
      {
        if(valeur.checked)
        {
          idElement = valeur.id;
          idTag = idElement.replace(/delete-tags-checkTag-/gi,'');
          tags=tags + idTag + " "; // Ajouter l'élément à la liste //
        }
      }

    idFile = (document.getElementById(elementId).parentNode).parentNode['id'];
    idFile = idFile.replace(/delete-tags-file-/gi,'');
    $.ajax({
      url: 'index.php',
      data: {'tags' : tags,'idFile':idFile,'action' : 'deleteTagFile'},
      dataType: 'json', 
      success: function (response) 
      {
        if( response.status === true )

        {
          alert('Tag(s) supprimé(s)');
          window.location.reload();
        }

        else alert(response);
      }

    });

  }
  

}

function addTagsFile(elementId)
{
  if (confirm("Confirmer l'ajout des tags."))
  {
    tags = ""; //le tableau//

    let checkboxesTags = document.getElementsByClassName('checkbox-add-tags');
    for(valeur of checkboxesTags)
      {
        if(valeur.checked)
        {
          idElement = valeur.id;
          idTag = idElement.replace(/add-tags-checkTag-/gi,'');
          tags=tags + idTag + " "; // Ajouter l'élément à la liste //
        }
      }

    idFile = (document.getElementById(elementId).parentNode).parentNode['id'];
    idFile = idFile.replace(/add-tags-file-/gi,'');
    console.log(idFile);
    console.log(tags);
    $.ajax({
      url: 'index.php',
      data: {'tags' : tags,'action' : 'addTagFile','idFile' : idFile},
      dataType: 'json', 
      success: function (response) 
      {
        if( response.status === true )

        {
          alert("Tag(s) ajouté(s)")
          //window.location.reload();
        }

        else alert('Something went wrong');
      }

    });

  }
  
}

function openAdd()
{
  document.getElementById('2021-dropdown-addDelete-tags-content').style.display='block';
}

function openMenuAddTagsMultipleFiles()
{
  document.getElementById("add-tags-multipleFiles").style.visibility="visible";
}

function openMenuDeleteTagsMultipleFiles()
{
  document.getElementById("delete-tags-multipleFiles").style.visibility="visible";
}

function closeDeleteTagsMultipleFiles()
{
  document.getElementById("delete-tags-multipleFiles").style.visibility="hidden";
}

function closeAddTagsMultipleFiles()
{
  document.getElementById("add-tags-multipleFiles").style.visibility="hidden";
}

function deleteTagsMultipleFiles()
{
  if (confirm("Confirmer la suppresion des tags."))
  {
    tags = ""; //le tableau//
    idFiles ="";

    let checkboxesTags = document.getElementsByClassName('checkbox-delete-tags-multipleFiles');
    for(valeur of checkboxesTags)
      {
        if(valeur.checked)
        {
          idElement = valeur.id;
          idTag = idElement.replace(/delete-tags-multipleFiles-checkTag-/gi,'');
          tags=tags + idTag + " "; // Ajouter l'élément à la liste //
        }
      }

    let checkboxesFiles = document.getElementsByClassName('checkbox-file');
    for(valeur of checkboxesFiles)
      {
        if(valeur.checked)
        {
          idElement = valeur.id;
          id = idElement.replace(/checkFile-/gi,'');
          idFiles=idFiles + id + " "; // Ajouter l'élément à la liste //
        }
      }

      console.log(idFiles);
      console.log(tags);
    $.ajax({
      url: 'index.php',
      data: {'tags' : tags,'action' : 'deleteTagsMultipleFiles','files' : idFiles},
      dataType: 'json', 
      success: function (response) 
      {
        if( response.status === true )

        {
          alert("Tag(s) supprimé(s)")
          window.location.reload();
        }

        else alert('Something went wrong');
      }

    });
  }
}

function addTagsMultipleFiles()
{

  if (confirm("Confirmer l'ajout des tags."))
  {

    tags = ""; //le tableau//
    idFiles ="";

    let checkboxesTags = document.getElementsByClassName('checkbox-add-tags-multipleFiles');
    for(valeur of checkboxesTags)
      {
        if(valeur.checked)
        {
          idElement = valeur.id;
          idTag = idElement.replace(/add-tags-multipleFiles-checkTag-/gi,'');
          tags=tags + idTag + " "; // Ajouter l'élément à la liste //
        }
      }

    let checkboxesFiles = document.getElementsByClassName('checkbox-file');
    for(valeur of checkboxesFiles)
      {
        if(valeur.checked)
        {
          idElement = valeur.id;
          id = idElement.replace(/checkFile-/gi,'');
          idFiles=idFiles + id + " "; // Ajouter l'élément à la liste //
        }
      }
    $.ajax({
      url: 'index.php',
      data: {'tags' : tags,'action' : 'addTagsMultipleFiles','files' : idFiles},
      dataType: 'json', 
      success: function (response) 
      {
        if( response.status === true )

        {
          alert("Tag(s) ajouté(s)")
          window.location.reload();
        }

        else alert('Something went wrong');
      }

    });
  }
}

function getFilesSelectedSize()
{
  idFiles ="";
  let checkboxesFiles = document.getElementsByClassName('checkbox-file');
    for(valeur of checkboxesFiles)
      {
        if(valeur.checked)
        {
          idElement = valeur.id;
          id = idElement.replace(/checkFile-/gi,'');
          idFiles=idFiles + id + " "; // Ajouter l'élément à la liste //
        }
      }

    $.ajax({
      url: 'index.php',
      data: {'action' : 'getFilesSize','files' : idFiles},
      dataType: 'json', 
      success: function (response) 
      {
        //console.log(response);
        //console.log(response['status']);
        size = 'Taille : ' + response + 'Mo';
        document.getElementById('sizeFilesSelected').textContent=size;
        
      }

    });



}


function downloadMultipleFiles()
{
  idFiles ="";
  let checkboxesFiles = document.getElementsByClassName('checkbox-file');
    for(valeur of checkboxesFiles)
      {
        if(valeur.checked)
        {
          idElement = valeur.id;
          id = idElement.replace(/checkFile-/gi,'');
          idFiles=idFiles + id + " "; // Ajouter l'élément à la liste //
        }
      }
    $.ajax({
      url: 'index.php',
      data: {'action' : 'downloadMultipleFiles','files' : idFiles},
      dataType: 'json', 
      success: function (response) 
      {
        if(response.status === true)
        {
          zipPath = response['zipPath'];
          element=document.getElementById('download-multipleFiles-link')
          element.setAttribute('href', zipPath);
          document.getElementById('popup-confirm-download-multipleFiles').style.display='inline-flex';
          
        }
        else{
          alert("Something went wrong")
        }
      }

    });



}

function closeConfirmationPopup()
{
  document.getElementById("popup-confirm-download-multipleFiles").style.display = 'none';
  document.getElementById("popup-options-multipleFiles").style.display = 'none';
}

function renameFile(event)
{
	let file = event.currentTarget;

	if (confirm("Confirmer le nouveau nom du fichier."))
	{
		$.ajax({
			url: 'index.php',
			data: {'idFile' : file.name, 'new_name' : file.value, 'action' : "renameFile"},
			dataType: 'json'
		});
		file.placeholder = file.value;
	}
	else {
		file.value = file.placeholder;
	}
}


//document.oncontextmenu = function(){return false}

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
			mime_types: [{title: "Image", extensions: "jpg,gif,png,hdr,tif,jif, jfif,jp2,jpx,j2k,j2c,fpx,pcd,pdf,jpeg,wbmp,avif,webp,xbm"},{title: "Video", extensions:  "3gp, 3g2, avi, asf,wav,wma,wmv,flv,mkv,mka,mks,mk3d,mp4,mpg,mxf,ogg,mov,qt,ts,webm,mpeg,mp4a,mp4b,mp4r,mp4v"}]
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

const title_files = document.querySelectorAll('.title-file');

title_files.forEach(file => {
	file.addEventListener('change', renameFile, false);
});