function sortDeleteDate()
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

function sortFileName()
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

function deleteFiles()
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
      data: {'idFiles' : idFiles,'action' : "deleteFiles"},
      dataType: 'json', 
      success: function (response) 
      {
        if( response.status === true )

        {
          alert('Fichier(s) supprimé(s)');
          window.location.reload();
        }

        else alert('Suppression impossible!');
      }

    });
  }
}

function recoveryFiles()
{
  if (confirm("Confirmer la restauration des fichiers."))
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
      data: {'idFiles' : idFiles,'action' : "recoveryFiles"},
      dataType: 'json', 
      success: function (response) 
      {
        if( response.status === true )

        {
          alert('Fichiers restaurés');
          window.location.reload();
        }

        else alert('Something Went Wrong!');
      }

    });
  }
}

function closeMultipleFiles()
{
  document.getElementById('popup-options-multipleFiles').style.display='none';
}
