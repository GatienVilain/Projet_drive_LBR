function deleteFile(idFichier)
{
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

function recoverFile(idFichier)
{
  $.ajax({
    url: 'index.php',
    data: {'idFile' : idFichier,'action' : "recoverFile"},
    dataType: 'json', 
    success: function (response) 
    {
      if( response.status === true )

      {
        alert('File Recovered!');
        window.location.reload();
      }

      else alert('Something Went Wrong!');
    }

  });
}