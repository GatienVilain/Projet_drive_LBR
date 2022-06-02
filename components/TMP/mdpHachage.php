<?php
/**
 * Nous voulons juste hacher notre mot de passe en utiliant l'algorithme par défaut.
 * Actuellement, il s'agit de BCRYPT, ce qui produira un résultat sous forme de chaîne de
 * caractères d'une longueur de 60 caractères.
 *
 * Gardez à l'esprit que DEFAULT peut changer dans le temps, aussi, vous devriez vous
 * y préparer en autorisant un stockage supérieur à 60 caractères (255 peut être un bon choix)
 */
$passewordHashe = password_hash("test", PASSWORD_DEFAULT);
echo $passewordHashe;
$bonMDP = password_verify("rasmuslerddorf",$passewordHashe);
if($bonMDP)
{
  echo("<br>MDP CORRECT");
}

else
{
  echo("<br>MDP INCORRECT");
}
  

?>