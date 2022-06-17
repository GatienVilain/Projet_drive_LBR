<link href="public/css/banner-storage.css" rel="stylesheet">

<footer id=banner-footer>

<?php
require_once("components/Tools/Database/DatabaseConnection.php");
use Application\Tools\Database\DatabaseConnection;
$connect = new DatabaseConnection();

$folderPathPictures = 'C:\wamp64\www\storage'.DIRECTORY_SEPARATOR.'pictures'.DIRECTORY_SEPARATOR;
$usedStorageSpace = repertoire_size($folderPathPictures);
$totalStorageSpace = (float)(disk_total_space("C:")/gmp_pow(10,9)); ?>

<style>

#storageBar {
    width:<?php echo(($usedStorageSpace*100)/$totalStorageSpace) ?>%;
}

</style>

<p id=banner-footer-role><?= $role ?></p>
<div id=banner-footer-storage>
    <p id=paragraph-storage>
        <?php if($connect->get_user($_SESSION["email"])["role"] == "admin"){
           echo("<span id = usedStorageSpace>".$usedStorageSpace."Go  </span> utilise(s) sur ".$totalStorageSpace."Go"."<div id=conteneurStorageBar>
           <div id=storageBar>
   
           </div>
       </div>");}
        ?>        
    </p>
    
</div> 

<p id=banner-footer-file><?= $nbr_files?> fichier(s)</p>

</footer>

<?php 
function repertoire_size($rep)
{
    $repSize = 0;
	$images = glob("$rep*.{jpg,jpeg,gif,png,bmp,webp}", GLOB_BRACE);
    foreach($images as $i)
    {
        $repSize += filesize($i);
    }

    return round($repSize/(float)gmp_pow(10,9), 3);
}
?>



