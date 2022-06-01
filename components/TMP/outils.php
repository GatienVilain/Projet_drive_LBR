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