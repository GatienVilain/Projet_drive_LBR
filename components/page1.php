<!DOCTYPE html>
<html>
<head>
    <title>test mdp</title>
  </head>
<body>

<h2>Zone de test identifiants</h2>

<?php

require 'auth.php';
forcer_utilisateur_connecter();
?>

<?php
include('outils.php');

if (isset($_POST['mail'])){
    if (verif_format_mail($_POST['mail']) === true){echo "Mail valide" ;}
    else{echo "Mail invalide " ;}
}



if (isset($_POST['mdptest'] )){
    if($_POST['mdptest']=== $_POST['mdptest2'] ){

        if (verif_format_mdp($_POST['mdptest']) === true){echo "Mot de passe valide" ;}
    else{echo "Mot de passe invalide" ;}
    }
    else{echo "ce ne sont pas les mêmes mdp";}
}
?>



<form action="" method="post">
    <div class="form-group">
    <div class="form-group">
        <input class="form-control" type="text" name= "mail" placeholder="adresse mail" required>
    </div>
        <input class="form-control" type="password" name= "mdptest" placeholder="ecrire un mot de passe" required>
    </div>
    <div class="form-group">
        <input class="form-control" type="password" name= "mdptest2" placeholder="confirmer votre mot de passe" required>
    </div>
    <input type="submit" name="button2"value="valider"/>
</form>

<?php
   // bouton de changement de page-----------------------------------------   
      if(isset($_POST['buttonindex'])) {
        header('Location: index.php');
        exit();
      }

  ?>

<form method="post">
    <input type="submit" name="buttonindex"value="retour index"/>
</form>