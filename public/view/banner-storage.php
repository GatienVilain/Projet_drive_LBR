<link href="public/css/banner-storage.css" rel="stylesheet">

<footer id=banner-footer>

<?php $filesNumber = 3;
$usedStorageSpace = 40;
$totalStorageSpace = 100 ?>

<style>

#storageBar {
    width:<?php echo(($usedStorageSpace*100)/$totalStorageSpace) ?>%;
}

</style>

<p id=banner-footer-role>Admin</p>
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

<p id=banner-footer-file><?php echo($filesNumber)?> fichier(s)</p>

</footer>


