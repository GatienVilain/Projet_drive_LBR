<?php

function creerMiniature($filePath)
{
	//On vérifie qu'il y a bien un fichier à l'adresse envoyée
    if(is_file($filePath))
    {
		//Extensions supportées pour la création de miniature
		$allow_ext = ["gif", "jpg", "jpeg", "png"];
		$ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
		//Si le fichier possède une extension compatible
		if(in_array($ext, $allow_ext))
		{
			$infos = getimagesize($filePath);
			//On récupère le type mime
			$mime = $infos['mime'];
			//On cherche le type mime correspondant au fichier
			switch ($mime) {
				//Fichier jpeg
				case 'image/jpeg':
					$image_create_func = 'imagecreatefromjpeg'; //Fonction pour générer la miniature
					$image_show_func = 'imagejpeg'; //Fonction pour sauvegarder la miniature
					break;
				//Fichier png
				case 'image/png':
					$image_create_func = 'imagecreatefrompng';
					$image_show_func = 'imagepng';
					break;
				//Fichier gif
				case 'image/gif':
					$image_create_func = 'imagecreatefromgif';
					$image_show_func = 'imagegif';
					break;

				default: 
					throw new Exception('Unknown image type.');	
			}
			list($width, $height) = getimagesize($filePath);//On récupère la taille de l'image
			$modwidth = 300;  //largeur visé
			$diff = $width / $modwidth;//Rapport longueur largeur
			$modheight = (int)($height / $diff);
			//Chemin de stockage de la miniature
			$miniaturePath = pathinfo($filePath, PATHINFO_DIRNAME).DIRECTORY_SEPARATOR."frames".DIRECTORY_SEPARATOR.pathinfo($filePath, PATHINFO_FILENAME).".".$ext;
			$tn = imagecreatetruecolor($modwidth, $modheight);
			//On crée la miniature à partir de l'image
			$image = $image_create_func($filePath);
			imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
			//On enregistre la miniature dans le dossier 'frames' du dossier 'pictures'	
			$image_show_func($tn, $miniaturePath);
		}
    }
}