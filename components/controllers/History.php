<?php

namespace Application\Controllers;

class History
{
    function execute()
    {
        $content = $this->tableaux();
        $error = "";
		require('public/view/history.php');
    }

    function tableaux()
    {
        $html='<article>';
        if($dossier=opendir('log')){
            $liste=array();
            while(false != $fichier = readdir($dossier)){
                $liste[]=$fichier;
            }
            $liste=array_reverse($liste);
            foreach ($liste as $fichier){
                if ($fichier != '.' && $fichier !='..'){
                    $chemin='log/';
                    $chemin.=$fichier;
                    $tmp = fopen($chemin, 'r');
                    $caractere='';
                    $chaine='';
                    $html.='<h4>';
                    $html.='historique du ';
                    $fichier=str_replace('.txt','',$fichier);
                    $html.=$fichier;
                    $html.='</h4>';

                    
                    $html.='<table>';
                        
                    while(!feof($tmp)){
                        
                        $caractere = fgets($tmp);
                        $chaine.=$caractere;
                    }
                    //$chaine=str_replace(';',' ',$chaine);
                    //$chaine.='fin';
                    //echo strlen($chaine);

                    $cpt=0;
                    while($cpt<strlen($chaine)){
                        $html.='<tr>';
                        //on ouvre la ligne
                        $cptbis=0;
                        $bloc='';
                        while(($cptbis<4) && ($cpt<strlen($chaine))){
                            
                            if($chaine[$cpt]!=';'){
                                $bloc.=$chaine[$cpt];
                                
                            }
                            else{    
                                $html.='<td>';
                                $html.=$bloc;     
                                $html.='</td>';   
                                $bloc='';
                                $cptbis++;
                                
                            }
                            $cpt++;

                        }
                        $cpt++;//pour sauter les retour à la ligne
                        $html.='</tr>';
                    
                    }
                }
                $html.='</table>';

            }
        }
        $html.='</article>';
        return $html;
    }
}