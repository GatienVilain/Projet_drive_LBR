<?php

namespace Application\Controllers\UsersModeration;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class DeleteUser
{
	public function execute()
	{   
        
        $connection = new DatabaseConnection();

		$liste_utilisateurs= $connection->get_all_users() ;
		sort($liste_utilisateurs);
        $liste_bouttons=$_POST;
        for ( $i=0; $i < count($liste_utilisateurs); $i++ )
        {   
            $tmp=$liste_utilisateurs[$i]['email'];
            $tmp=str_replace('.','_',$tmp);
            if (isset($liste_bouttons[$tmp]))
            {
                if ($liste_bouttons[$tmp]=='on')
                {
                    // echo $liste_utilisateurs[$i]['email'];
                    $result = $connection->delete_user($liste_utilisateurs[$i]['email']);
                    // echo $result;
                }
            }
        }

        //print_r($liste_bouttons);

        
		header('Location: index.php?action=usersmoderation');
	}
}