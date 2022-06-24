# Créer la base de données

Pour créer la base de données, copier-coller le contenu du fichier drive.txt
dans la console SQL de votre gestionnaire de base de donnée.

# Modification des informations relatives à la base de données

Si vous souhaitez mettre un mot de passe ou modifier des informations de la base de données (mot de passe, nom etc.).
Veuillez modifier également ces deux fichiers suivants :

components/Tools/Database/DatabaseConnection.php
components/Tools/Upload/upload.php

protected const host = "localhost";
protected const user = "root";
protected const password = "";
protected const db = "drive";

# Paramétrage de l'envoie de mails

Pour synchroniser le site au serveur mail, modifier le mail indiqué dans la classe Email.php
situé dans compoments/Model/Email.php

# Connexion au site web

Le site web est protégé par une authentification avec mot de passe chiffré.

Le compte administrateur par défaut étant :

E-mail : contact@lesbriquesrouges.fr
Mot de passe : Briques_Rouges2022