<?php

require_once("components\Tools\Database\DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

function testTableau()
{
    $connection = new DatabaseConnection();

    $liste_utilisateurs= $connection->get_all_users() ;
    $nbdutilisateurs=count($liste_utilisateurs);
    echo $nbdutilisateurs ;
    echo $connection->get_user($liste_utilisateurs[3]['email'])["prenom"];

    //sort($liste_utilisateurs);
    ?>
    <form action = "" method= "post">
    <table>
    <?php

    for($i=0;$i<$nbdutilisateurs;$i++){
        $prenom=$connection->get_user($liste_utilisateurs[$i]['email'])["prenom"] ;
        $nom=$connection->get_user($liste_utilisateurs[$i]['email'])["nom"] ;
        $role=$connection->get_user($liste_utilisateurs[$i]['email'])["role"];
        ?><tr><?php //on ouvre la ligne
        ?><td><input type="checkbox" name="<?php $i ?>"><?php
        ?><td><?php echo $prenom; ?></td><?php
        ?><td><?php echo $nom; ?></td><?php
        ?><td><?php echo $role; ?></td><?php
        echo $i;




        ?></tr><?php
    }
    ?>
    </table>
    </form>
<?php
}
?>
