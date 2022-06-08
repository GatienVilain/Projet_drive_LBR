<?php

namespace Application\Model;

class Log
{
    public function ecrire_log($user,$action)
    {
        $date=date("d/m/Y");
        $texte=$date;
        $texte.=';';
        $texte.=date("H:i:s");
        $texte.=';';
        $texte.=$user;
        $texte.=';';
        $texte.=$action;
        $texte.=';';
        $texte.="\n";
        $dest="log/";
        $date=str_replace('/','-',$date);
        $dest.=$date;
        echo $date;
        $dest.='.txt';
        file_put_contents($dest,$texte,FILE_APPEND);
        echo'succes ecriture';
    }
}



