<?php

namespace Application\Controllers;

class History
{
    function execute()
    {
        
    if($dossier=opendir('log')){
        while(false != $fichier = readdir($dossier)){
            if ($fichier != '.' && $fichier !='..'){
                $chemin='log/';
                $chemin.=$fichier;
                $tmp = fopen($chemin, 'r');
                $caractere='';
                $chaine='';
                //echo $fichier;
                ?>
                <table>
                    <?php
                while(!feof($tmp)){
                    
                    $caractere = fgets($tmp);
                    $chaine.=$caractere;
                }
                //$chaine=str_replace(';',' ',$chaine);
                //$chaine.='fin';
                //echo strlen($chaine);
                $cpt=0;


                while($cpt<strlen($chaine)){
                    ?><tr><?php //on ouvre la ligne
                    $cptbis=0;
                    $bloc='';
                    while($cptbis<4){
                        
                        if($chaine[$cpt]!=';'){
                            $bloc.=$chaine[$cpt];
                            
                        }
                        else{                    
                            ?><td><?php echo $bloc; ?></td><?php
                            $bloc='';
                            $cptbis++;
                            
                        }
                        $cpt++;

                }
                $cpt++;//pour sauter les retour à la ligne
                ?></tr><?php
                
            }
                }
                ?></table><?php
            }
        }
    }
}