//Fonction permettant de classer part date de suppression
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
        //En cas de succès on recharge la page pour voir les changements
        window.location.reload();
      }
      else alert('Erreur!');
    }
  });
}

//Fonction permettant de classer par ordre alphabétique ou inverse
function sortFileName()
{
  $.ajax({
    url: 'index.php',
    data: {'option' : 'sortAlphabetic','action' : 'sortMaj'},
    dataType: 'json', 
    success: function (response) 
    {
      if( response.status === true )
      {
        window.location.reload();
      }
      else alert('Erreur!');
    }
  });
}

//Fonction permettant de supprimer définitivement un ou plusieurs fichiers
function deleteFiles()
{
  //On demande confirmation à l'utilisateur
  if (confirm("Confirmer la suppresion des fichiers."))
  {
    idFiles = ""; //le tableau//
    //On récupère les id de tous les fichiers sélectionnés
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

//Fonction permettant de restaurer un ou plusieurs fichiers
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
          alert('Fichier(s) restauré(s)');
          window.location.reload();
        }
        else alert('Restauration impossible!');
      }
    });
  }
}

//Fonction permettant de fermer la popup options
function closeMultipleFiles()
{
  document.getElementById('popup-options-multipleFiles').style.display='none';
}
