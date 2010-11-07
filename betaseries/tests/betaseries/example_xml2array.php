<?php

/**
 *
 * Utilisation de l'API BetaSeries.
 * Exemple 2 : Identification d'un membre puis utilisation du token.
 *
 * @package BetaSeries
 *
 */
require_once '../../config/config_betaseries.php';
require_once '../../includes/betaseries/class_betaseries.php';

/**
 *
 * Clé API, nom et mot de passe utilisateur à renseigner.
 *
 */

$API_KEY = constant('BETASERIES_API_KEY');
$ACCOUNT_USERNAME = constant('ACCOUNT_USERNAME');
$ACCOUNT_PASSWORD = constant('ACCOUNT_PASSWORD');

/**
 *
 * Construction de la classe avec la clé API.
 *
 */

$b = new BetaSeries($API_KEY);

/**
 *
 * Appel de l'API pour identifier l'utilisateur.
 *
 */

$xml = $b->send_request('http://api.betaseries.com/members/auth.xml',array('login' => $ACCOUNT_USERNAME , 'password' => md5($ACCOUNT_PASSWORD)));

/**
 *
 * On met le token de l'utilisateur dans une variable et on
 * configure la classe avec celle-ci.
 *
 */

$token = (string)$xml->member->token;
$b->set_token($token);

/**
 *
 * Appel de l'API pour afficher des informations de base sur le membre.
 *
 */

$xml = $b->send_request('http://api.betaseries.com/members/infos.xml');

/**
 *
 * Sortie var_dump() de l'objet récupéré.
 *
 */
var_dump($xml);

?>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<?php
$xmlArray=xml2array($xml);
var_dump($xmlArray);
?>