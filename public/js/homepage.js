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

/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction(idElement) 
{

  idElement=idElement+'-content';
  document.getElementById(idElement).classList.toggle("show");

}

