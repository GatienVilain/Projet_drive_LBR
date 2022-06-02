<?php
session_start();
if (isset($_POST['codeverif'])){
    if ($_POST['codeverif']==$_SESSION['code']){
        header('Location: page1.php');
        
        exit();

    }
    else{echo "mauvais code de récupération";}
}
?>



<form action="" method="post">  

    <div class="form-group">
        <input class="form-control" type="text" name= "codeverif" placeholder="entrez le code reçu par mail" required>
    </div>
    <input type="submit" name="button2"value="valider"/>

    </form>