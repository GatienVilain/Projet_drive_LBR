<?php

namespace Application\Controllers\UsersModeration;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class GetUsersModeration
{
	public function execute()
	{
		$userTable = $this->UserTable();
		$error = "";
		require('public/view/usersmoderation.php');
	}

	function UserTable()
	{

		$connection = new DatabaseConnection();
		$utilisateur=$_SESSION['email'];

		$liste_utilisateurs= $connection->get_all_users() ;
		sort($liste_utilisateurs);
		$cpt=0;
		while($liste_utilisateurs[$cpt]['email']!=$utilisateur){
			$cpt++;
		}
		array_splice($liste_utilisateurs[$cpt],$cpt);

		$nbdutilisateurs=count($liste_utilisateurs);
		$html='<div id="liste" ><form action = "index.php?action=usersmoderation" method= "post"><table>';


		for($i=0;$i<$nbdutilisateurs;$i++){
			if ($i != $cpt){
			$prenom=$connection->get_user($liste_utilisateurs[$i]['email'])["prenom"] ;
			$nom=$connection->get_user($liste_utilisateurs[$i]['email'])["nom"] ;
			$role=$connection->get_user($liste_utilisateurs[$i]['email'])["role"];
			$descrition=$connection->get_user($liste_utilisateurs[$i]['email'])["descriptif"];
			$html.='<tr>';//on ouvre la ligne
			$html.='<td class="checkbox">';
			$html.='<input type="checkbox" name="';
			$html.=$liste_utilisateurs[$i]['email'];
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
			$html.='<td class="email">';
			$html.=$liste_utilisateurs[$i]['email'];
			$html.='</td>';
			$html.='<td class="description">';
			$html.=$descrition;
			$html.='</td>';
			$html.='</tr>';
			}
		}
		$html.='</table>';
		$html.='<span id="ajouter"><input type="submit" name="button" value="ajouter"></span>';
		$html.='<span id="modifier"><input type="submit" name="button" value="modifier"><input type="submit" name="button" value="supprimer"></span></form>';

		$html.='</div>';
		return $html;
	}
}
