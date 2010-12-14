<?php
/*
 * Created on 22 oct. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: config_betaseries.php
 * 
 */
//clé et adresse de l'api bétaséries
define('BETASERIES_API_KEY','Clé API');
define('BETASERIES_API_ADRESS','http://api.betaseries.com/');
//Pour les tests avec plusieurs utilisateurs (juste exemple_multi_accounts pour l'instant)
define('ACCOUNT_USERS',serialize(array(
'login1' => 'password1',
'login2' => 'password2',
'login3' => 'password3',
//Cet utilisateur stoque les fichiers. Cela permet de savoir si les fichiers sont téléchargés ou non.
'login.De.L.utilisateur.Qui.Stoque.Les.Fichiers' => 'password.De.L.utilisateur.Qui.Stoque.Les.Fichiers',
 )));
//Cet utilisateur stoque les fichiers. Cela permet de savoir si les fichiers sont téléchargés ou non.
define('SERVER_ACCOUNT','login.De.L.utilisateur.Qui.Stoque.Les.Fichiers');
//Le chemin du dossier dans lequel le script est stoqué
//ATTENTION: NE PAS AJOUTER DE / à la fin du chemin du dossier
define('FOLDER_PATH','/betaseries/multiUsers');
?>
