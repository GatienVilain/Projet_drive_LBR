<link href="public/css/banner-storage.css" rel="stylesheet">

<footer id=banner-footer>

<?php 
$folderPath = __DIR__.'/../../storage/pictures/';
$usedStorageSpace = repertoire_size($folderPath);
$totalStorageSpace = (float)(disk_total_space("C:")/gmp_pow(10,9)); ?>

<style>

#storageBar {
    width:<?php echo(($usedStorageSpace*100)/$totalStorageSpace) ?>%;
}

</style>

<p id=banner-footer-role><?= $role ?></p>
<div id=banner-footer-storage>
    <p id=paragraph-storage>
        <span id = usedStorageSpace>
            <?= $usedStorageSpace ?>Go
        </span> utilisé(s) sur <?php echo(' '.$totalStorageSpace)?>Go
    </p>
    <div id=conteneurStorageBar>
        <div id=storageBar>

        </div>
    </div>
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



