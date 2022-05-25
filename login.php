<?php
$erreur= null;

if (!empty($_POST['mail']) && (!empty($_POST['mdp']))) {
    //on teste les identifiants
    if ($_POST['mail'] === 'celestincaptal@gmail.com' && $_POST['mdp'] === '1234'){
        //on connecte
         session_start();
         $_SESSION['connecte']=1;
         header('Location: index.php');
         exit();
    }
    else{
        $erreur = "Identifiants incorrects";
    }
}

require 'auth.php';
if(est_connecte()){
    header('Location: /index.php');
    exit();
}
?>


<?php if ($erreur): ?>
<div class="alert alert-danger">
    <?= $erreur ?>
</div>
<?php endif ?>



<form action="" method="post">
    <div class="form-group">
        <input class="form-control" type="text" name= "mail" placeholder="mail utilisateur">
    </div>
    <div class="form-group">
        <input class="form-control" type="password" name= "mdp" placeholder="mot de passe">
    </div>
    <button type="submit" class="btn btn-primary">Se connecter</button>

</form>

