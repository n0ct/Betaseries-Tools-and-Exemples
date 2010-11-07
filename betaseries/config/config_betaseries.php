<?php
/*
 * Created on 22 oct. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: config_betaseries.php
 * 
 * ATTENTION: IL FAUT RENOMER CE FICHIER EN config_betaseries.php pour que cela fonctionne !
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
//Pour les tests avec 1 utilisateur (tous sauf exemple_multi_accounts)
define('ACCOUNT_USERNAME','login1');
define('ACCOUNT_PASSWORD','password1');
//Cet utilisateur stoque les fichiers. Cela permet de savoir si les fichiers sont téléchargés ou non.
define('SERVER_ACCOUNT','login.De.L.utilisateur.Qui.Stoque.Les.Fichiers');
//Chemin de sauvegarde des sous-titres (inutile pour l'instant car non utilisé dans les tests)
define('DOWNLOAD_PATH','Le dossier où vous voulez stoquer les fichiers');
?>
