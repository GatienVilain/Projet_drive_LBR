<?php
$erreur= null;

require_once 'sql.php';

if (!empty($_POST['mail']) && (!empty($_POST['mdp']))) {

    //on teste les identifiants
	$mail = $_POST['mail'];
	$mdp = $_POST['mdp'];
	
	$sql = new sql();
	
	$result = $sql->get_user_password($mail);
    if ($result != -1 && $result['mot_de_passe'] == $mdp){
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

<form method="post">
    <input type="submit" name="mdp_oublie"value="mdp oublié"/>
</form>

<?php
       if(isset($_POST['mdp_oublie'])) {
            header('Location: recupmdp.php');
            exit();
          }     

  ?>