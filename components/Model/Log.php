<?php

namespace Application\Model;

class Log
{
    public function ecrire_log($user, $action): void
    {
        $date = date("d/m/Y");

        $texte = $date . ';'
            . date("H:i:s") . ';'
            . $user . ';'
            . $action . ";\n";

        $date = str_replace('/','-',$date);
        $dest = "log/" . $date . '.txt';

        file_put_contents($dest,$texte,FILE_APPEND);
    }
}



