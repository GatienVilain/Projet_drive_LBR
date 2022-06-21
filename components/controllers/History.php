<?php

namespace Application\Controllers;

require_once("components/Model/Log.php");

use Application\Model\Log;

class History
{
    function execute()
    {
        $logs_table = $this->tableaux();

		require('public/view/history.php');
    }

    function tableaux()
    {
        $table = array();

        // si le dossier log existe et on arrive à l’ouvrir
        if( $dossier = opendir('log') )
        {
            $liste = array();

            // lis chaque fichier dans le dossier
            while(false != $fichier = readdir($dossier))
            {
                $liste[] = $fichier;
            }

            // Organise les fichiers dans l’ordre chronologique inverse
            $liste = array_reverse($liste);

            foreach ($liste as $fichier)
            {
                if ($fichier != '.' && $fichier !='..')
                {
                    // Ouvre le fichier de log
                    $chemin = 'log/' . $fichier;
                    $tmp = fopen($chemin, 'r');

                    $date = str_replace('.txt', '', $fichier);


                    $caractere = '';
                    $chaine = '';
                    while (!feof($tmp))
                    {
                        $caractere = fgets($tmp);
                        $chaine .= $caractere;
                    }

                    $cpt = 0;
                    $row_number = 0;
                    while ( $cpt < strlen($chaine) )
                    {
                        $element = 1;
                        $bloc = '';
                        while ( ($element <= 4) && ($cpt < strlen($chaine)) )
                        {
                            if ($chaine[$cpt] != ';')
                            {
                                $bloc .= $chaine[$cpt];
                            }
                            else {
                                if ($element == 1)
                                {
                                    $table[$date][$row_number]['date'] = $bloc;
                                }
                                elseif ($element == 2)
                                {
                                    $table[$date][$row_number]['hour'] = $bloc;
                                }
                                elseif ($element == 3)
                                {
                                    $table[$date][$row_number]['email'] = $bloc;
                                }
                                else {
                                    $table[$date][$row_number]['message'] = $bloc;
                                }

                                $bloc = '';
                                $element++;
                            }
                            $cpt++;
                        }
                        $row_number++;
                        $cpt++;//pour sauter les retour à la ligne
                    }
                }
            }
        }

        ( new Log() )->ecrire_log($_SESSION['email'],'à consulté les logs');

        return $table;
    }
}