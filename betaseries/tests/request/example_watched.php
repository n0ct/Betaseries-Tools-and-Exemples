<?php
/*
 * Created on 22 oct. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: example_request.php
 */
 ?>
 
 <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
 show url: <input type="text" class="text" name="show" id="show" value="dexter" size="30" maxlength="30" />
 <br />season number: <input type="text" class="text" name="season" id="season" value="05" size="3" maxlength="2" />
 <br />episode number: <input type="text" class="text" name="episode" id="episode" value="06" size="3" maxlength="2" />
 <br ><br><input type="submit" class="submit button" value="Envoyer" />
 </form>
 <?
 if (!(isset($_GET['show']) && isset($_GET['season']) && isset($_GET['episode']))) die ("");
 require_once '../../includes/betaseries/class_betaseries.php';
 require_once '../../config/config_betaseries.php';
 require_once '../../metier/class_BetaSeriesRequest.php';
 $API_KEY = constant('BETASERIES_API_KEY');
 $ACCOUNT_USERS=unserialize(constant('ACCOUNT_USERS'));
 
 $request=BetaSeriesRequest::getInstance();
 $ACCOUNT_USERNAME = '';
 $ACCOUNT_PASSWORD = '';
 foreach ($ACCOUNT_USERS as $login => $password)
 {
 	if ($login == constant('SERVER_ACCOUNT'))
	{
		 $ACCOUNT_USERNAME = $login;
		 $ACCOUNT_PASSWORD = $password;
	}
 }
 
 $request=BetaSeriesRequest::getInstance();
 
echo "<br />Test Utilisateur $login ajout&eacute;: ";
try{
	$request->addUser($ACCOUNT_USERNAME,$ACCOUNT_PASSWORD);
}
catch(Exception $e)
{
	exception_handler($e);
}
echo '<br /> Test ContainsUser apr&egrave;s insertion: '; 
if($request->containsUser($ACCOUNT_USERNAME)) echo "ok";
else echo "error";
 
 $serie=htmlentities($_GET['show']);
 $season=intval($_GET['season']);
 $episode=intval($_GET['episode']);
 
 $xml=$request->userRequest($ACCOUNT_USERNAME,"members/watched/".$serie.'.xml?season='.$season.'&episode='.$episode);
 echo "
 <br />
 <br />
 ";
 print_r($xml);
 echo "
 <br />
 <br />
 ";
 try {
 	$xmlResult_EpisodesToWatch=$request->userRequest($ACCOUNT_USERNAME,"members/episodes/vf.xml");
 	print_r($xml);
 }
 catch (Exception $e)
 {
 	exception_handler($e);
 }
?>