<?php

function creerMiniatureImage($imagePath)
{
    if(!empty($imagePath))
    {

        $allow_ext = ["gif", "jpg", "jpeg", "png"];
        $ext = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

        if(in_array($ext, $allow_ext))
        {
            $infos = getimagesize($imagePath);
            $mime = $infos['mime'];

            switch ($mime) {
                case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_show_func = 'imagejpeg';
                    break;

                case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_show_func = 'imagepng';
                    break;

                case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_show_func = 'imagegif';
                    break;

                default: 
                    throw new Exception('Unknown image type.');
                    
            }

            list($width, $height) = getimagesize($imagePath);
            $modwidth = 120;  //target width
            $diff = $width / $modwidth;
            $modheight = (int)($height / $diff);

            $miniaturePath = pathinfo($imagePath, PATHINFO_DIRNAME).DIRECTORY_SEPARATOR."frames".DIRECTORY_SEPARATOR.pathinfo($imagePath, PATHINFO_FILENAME).".".$ext;


            $tn = imagecreatetruecolor($modwidth, $modheight);
            $image = $image_create_func($imagePath);
            imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
            
            
            $image_show_func($tn, $miniaturePath);

        }
    }

}
 
?>


