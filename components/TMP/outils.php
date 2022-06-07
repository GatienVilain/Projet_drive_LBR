<?php
function verif_format_mdp($mdp)
{
	$majuscule = preg_match('@[A-Z]@', $mdp);
	$minuscule = preg_match('@[a-z]@', $mdp);
	$chiffre = preg_match('@[0-9]@', $mdp);
	$pattern=preg_match('/[\'\/~`\!@#$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $mdp);
	
	if(!$majuscule || !$pattern || !$minuscule || !$chiffre || strlen($mdp) < 8)
	{
		return false;
	}
	else 
		return true;
}
?>

<?php
function verif_format_mail($mail)
{
	if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
		return true;
	} else {
		return false;
	}
	
}
?>

<?php
function ecrire_log($user,$action){
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
