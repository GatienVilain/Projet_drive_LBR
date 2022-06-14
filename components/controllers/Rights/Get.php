<?php

namespace Application\Controllers\Rights;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class GetRights
{
    public function execute()
    {
        $connection = new DatabaseConnection();

        $categories = $connection->get_tag_category();

        $table = array();
        foreach ($categories as $value)
        {
            $key = $value["nom_categorie_tag"];

            $table[$key] = [];

        }

        print_r($table);
    }





    function truc(){
        $error = "";
        
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
                if (isset($liste_bouttons[$tmp]))
                {
                    if ($liste_bouttons[$tmp]=='on')
                    {
                        $cat_tag = $connection->get_rights_of_user($liste_utilisateurs[$i]['email']);

                        print_r($cat_tag);
                    }
                }
            }
        }

        foreach ($cat_tag as $tag)
        {
            $connection->get_tag($tag["id_tag"]);


        }
    }
}