<?php
/*
 * Created on 22 oct. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: example_request.php
 */
 

 require_once '../../includes/betaseries/class_betaseries.php';
 require_once '../../config/config_betaseries.php';
 require_once '../../metier/class_BetaSeriesRequest.php';
 
 $API_KEY = constant('BETASERIES_API_KEY');
 $ACCOUNT_USERNAME = constant('ACCOUNT_USERNAME');
 $ACCOUNT_PASSWORD = constant('ACCOUNT_PASSWORD');
 
 $request=BetaSeriesRequest::getInstance();
 
 echo "<br />Test Utilisateur ajout&eacute;: "/
 $request->addUser($ACCOUNT_USERNAME,$ACCOUNT_PASSWORD);
 
 echo '<br /> Test ContainsUser apr&egrave;s insertion:'; 
 echo $request->containsUser($ACCOUNT_USERNAME);
 
 echo "
 <br />
 <br />
 ";
 
 print_r($request->userRequest($ACCOUNT_USERNAME,"members/infos.xml"));
 
 echo "
 <br />
 <br />
 ";
 
 print_r($request->userRequest($ACCOUNT_USERNAME,'shows/episodes/himym.xml',array('season' => 1)));
?>