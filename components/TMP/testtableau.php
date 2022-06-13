<?php

require_once("components\Tools\Database\DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

function testTableau()
{

    $connection = new DatabaseConnection();

    $liste_utilisateurs= $connection->get_all_users() ;
    sort($liste_utilisateurs);
    $nbdutilisateurs=count($liste_utilisateurs);
    echo $nbdutilisateurs;
    $html='<form action = "" method= "post"><table>';


    for($i=0;$i<$nbdutilisateurs;$i++){
        $prenom=$connection->get_user($liste_utilisateurs[$i]['email'])["prenom"] ;
        $nom=$connection->get_user($liste_utilisateurs[$i]['email'])["nom"] ;
        $role=$connection->get_user($liste_utilisateurs[$i]['email'])["role"];
        $html.='<tr>';//on ouvre la ligne
        $html.='<td>';
        $html.='<input type="checkbox" name="';
        $html.=strval($i);
        $html.='"></td>';
        $html.='<td>';
        $html.=$prenom;
        $html.='</td>';
        $html.='<td>';
        $html.=$nom;
        $html.='</td>';
        $html.='<td>';
        $html.=$role;
        $html.='</td>';
        $html.='</tr>';
    }
    $html.='</table></form>';
    return $html 
}
?>
