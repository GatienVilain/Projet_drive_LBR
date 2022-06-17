//document.oncontextmenu = function(){return false}




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

function openPopupNewTag()
{

  document.getElementById("popup-newTag").style.visibility = "visible";
  
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

function deleteFile(idFichier)
{

  //var file_path = "storage/pictures/58.png";
  $.ajax({
    url: 'index.php',
    data: {'idFile' : idFichier,'action' : "deleteFile"},
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

function addNewTag()
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

function addNewCategory()
{

  var categoryName;
  categoryName = document.getElementById("popup-newCategory-nameCategory").value;
  //var file_path = "storage/pictures/58.png";
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

      else alert('Something Went Wrong!');
    }

  });
}

function trier()
{

  tags = ""; //le tableau//
  extensions = "";
  //authors = [];
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
        idTag = idElement.replace(/-filterMenu-checkExtension/gi,'');
        extensions=extensions + idTag + " "; // Ajouter l'élément à la liste //
      }
    }

    //console.log(extensions);
  $.ajax({
    url: 'index.php',
    data: {'tags' : tags,'extensions' : extensions,'action' : 'sortMaj'},
    dataType: 'json', 
    success: function (response) 
    {
      if( response.status === true )

      {
        window.location.reload();
      }

      else alert(response);
    }

  });

}
  

/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction(idElement) 
{

  idElement=idElement+'-content';
  document.getElementById(idElement).classList.toggle("show");

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

      else alert(response);
    }

  });

}

function editTag(idElement)
{
  idTag = idElement.replace(/editTag-button-validate/gi,"");
  newNameTag = document.getElementById("popup-editTag-nameTag").value;
  selectedCategory = document.getElementById("popup-editTag-selectCategory").options[document.getElementById('popup-editTag-selectCategory').selectedIndex].text
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

      else alert(response);
    }

  });

}

function deleteCategory(idElement)
{
  categoryName = idElement.replace(/-dropdown-delete/gi,"");
  $.ajax({
    url: 'index.php',
    data: {'categoryName' : categoryName,'option' : 'deleteCategory','action' : 'deleteTagOrCategory'},
    dataType: 'json', 
    success: function (response) 
    {
      if( response.status === true )

      {
        alert('Catégorie supprimée');
        window.location.reload();
      }

      else alert(response);
    }

  });

}

function editCategory(idElement)
{
  categoryName = idElement.replace(/-edit-cetegoryName/gi,"");
  newName = document.getElementById("popup-editCategory-nameCategory").value;
  $.ajax({
    url: 'index.php',
    data: {'categoryName' : categoryName,'option' : 'editCategory','newName' : newName,'action' : 'editTagOrCategory'},
    dataType: 'json', 
    success: function (response) 
    {
      if( response.status === true )

      {
        alert('Catégorie modifée');
        window.location.reload();
      }

      else alert(response);
    }

  });

}

