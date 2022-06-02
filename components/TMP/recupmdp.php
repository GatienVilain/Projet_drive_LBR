<?php

session_start();

if (!empty($_POST['mail'])&& (isset($_POST['mail'] ))){

    $mail = $_POST['mail'];
    $subject="code de recupération de mdp";
    $message="";
    for($i=0; $i<9;$i++){
        $message.= mt_rand(0,9);
    }

    $headers="Content-Type: text/plain; charset=utf-8\r\n";
    $headers.= "From: totolvroum@gmail.com\r\n";

    if(mail($mail,$subject,$message,$headers)){
        
        echo "code envoyé";
        $_SESSION['code']=$message;
        $_SESSION['mail']=$mail;
        header('Location: testcoderecup.php');
        exit();
    }

}


?>

<form action="" method="post">


<div class="form-group">
    <input class="form-control" type="text" name= "mail" placeholder="mail utilisateur">
</div>
<button type="submit" class="btn btn-primary">reinitialiser mdp</button>
</form>

