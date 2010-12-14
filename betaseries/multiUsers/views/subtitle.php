<?php
/*
 * Created on 22 oct. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: example_request.php
 */
 $show=null;
 $season=null;
 $episode=null;
 if ((isset($_GET['show']) && isset($_GET['season']) && isset($_GET['episode'])))
 {
 	$show=$_GET['show'];
 	$season=$_GET['season'];
 	$episode=$_GET['episode'];
 }
 else
 {
 	die('Vous ne pouvez acc&eacute;der directement &agrave; cette page');
 }

 require_once(dirname(__FILE__).'/../config/config_betaseries.php');
 require_once(dirname(__FILE__).'/../core/exception/exception_handler.php');
 require_once(dirname(__FILE__).'/../core/betaseries/class_BetaSeriesRequest.php');
 
 $API_KEY = constant('BETASERIES_API_KEY');
 $ACCOUNT_USERNAME = constant('ACCOUNT_USERNAME');
 $ACCOUNT_PASSWORD = constant('ACCOUNT_PASSWORD');
 $SERVER_ADDR=$_SERVER['HTTP_HOST'];
 $request=BetaSeriesRequest::getInstance();
 
 $request->addUser($ACCOUNT_USERNAME,$ACCOUNT_PASSWORD);
 if(!$request->containsUser($ACCOUNT_USERNAME)) die("Erreur: login ou mot de passe incorrect.");
 //print_r($request->userRequest($ACCOUNT_USERNAME,"members/infos.xml"));
 $xml=$request->userRequest($ACCOUNT_USERNAME,'subtitles/show/'.$show.'.xml',array('language' => 'VF', 'season' => $season,'episode' => $episode));
 echo "<table border=\"0\">";
 $first=true;
 foreach($xml->subtitles->subtitle as $sub)
 {
	if($first)
	{
		$first=false;
		echo '<tr><td colspan="3"><b><u>'.$sub->title.'  s'.$sub->season.'e'.$episode.' sous-titres VF:</b></u></td></tr>';
	}
	echo '<tr><td>'.$sub->file.'</td><td><a href="'.$sub->url.'"><img border="0" src="http://'.$SERVER_ADDR.constant("FOLDER_PATH").'/images/srt.png" alt="'.$sub->source.'"/></a></td></tr>';
 }
 echo "</table>";
?>