<!DOCTYPE html>
<html>
<head>
    <title>index</title>
  </head>
<body>

<h2>Page Principale</h2>

<?php

require 'auth.php';
forcer_utilisateur_connecter();
?>




<?php
   // bouton de changement de page-----------------------------------------   
      if(isset($_POST['buttonpage1'])) {
        header('Location: page1.php');
        exit();
      }

  ?>

<form method="post">
    <input type="submit" name="buttonpage3"value="aller à la page google"/>
</form>
<?php
// bouton de changement de page google   -----------------------------------------   
      if(isset($_POST['buttonpage3'])) {
        header('Location: pagegoogle.php');
        exit();
      }

  ?>

<form method="post">
    <input type="submit" name="buttonpage1"value="aller à la page 1"/>
</form>


<! bouton de deco----------------------------------------- >   
<?php
 
      if(isset($_POST['button1'])) {
          logout();
      }

  ?>
    
<form method="post">
    <input type="submit" name="button1"value="déconnection"/>
</form>


</body>
</html>




