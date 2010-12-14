<?php
/*
 * Created on 22 oct. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: example_request.php
 */
 require_once(dirname(__FILE__).'/../config/config_betaseries.php');
 require_once(dirname(__FILE__).'/../core/exception/exception_handler.php');
 require_once(dirname(__FILE__).'/../core/betaseries/class_BetaSeriesRequest.php');
 $ACCOUNT_USERS=unserialize(constant('ACCOUNT_USERS'))
 ?>
 
 <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
 login : <select name="login" size="<?=count($ACCOUNT_USERS)?>">
							<?php
							foreach ($ACCOUNT_USERS as $login => $password)
							{
	 							echo '<option value="'.$login.'">'.$login.'</option>';
	 						}
							?>
</select>
<br />show url: <input type="text" class="text" name="show" id="show" value="<?php
if (isset($_GET['show'])) echo $_GET['show'];
else echo "unitedstatesoftara";
?>" size="30" maxlength="30" />
 <br />season number: <input type="text" class="text" name="season" id="season" value="<?php
if (isset($_GET['season'])) echo $_GET['season'];
else echo "02";
?>" size="3" maxlength="2" />
 <br />episode number: <input type="text" class="text" name="episode" id="episode" value="<?php
if (isset($_GET['episode'])) echo $_GET['episode'];
else echo "01";
?>" size="3" maxlength="2" />
 <br ><br><input type="submit" class="submit button" value="Envoyer" />
 </form>
 <?
 if (!(isset($_GET['show']) && isset($_GET['season']) && isset($_GET['episode']))) die ("");
 $request=BetaSeriesRequest::getInstance();
 if (!isset($_GET['login'])) $seekedLogin=constant('SERVER_ACCOUNT');
 else
 {
	$ACCOUNT_USERS=unserialize(constant('ACCOUNT_USERS'));
	
	$ACCOUNT_USERNAME = null;
	$ACCOUNT_PASSWORD = null;
 }
 if (isset($_GET['login'])) $seekedLogin=$_GET['login'];
 else $seekedLogin=constant('SERVER_ACCOUNT');
 foreach ($ACCOUNT_USERS as $login => $password)
 {
 	if ($login == $seekedLogin)
	{
		 $ACCOUNT_USERNAME = $login;
		 $ACCOUNT_PASSWORD = $password;
		 break;
	}
 }
 
 $request=BetaSeriesRequest::getInstance();
 
/*echo "<br />Test Utilisateur $ACCOUNT_USERNAME ajout&eacute;: ";*/
try{
	$request->addUser($ACCOUNT_USERNAME,$ACCOUNT_PASSWORD);
}
catch(Exception $e)
{
	echo($e->message());
}
if(!$request->containsUser($login)) die("<br />Erreur: l'utilisateur $login n'a pu &ecirc;tre ajout&eacute;. Login ou mot de passe incorrect");
 
 $serie=htmlentities($_GET['show']);
 $season=$_GET['season'];
 $episode=$_GET['episode'];
 /*echo '<br />Faites clic droit afficher la source pour plus d\'informations.





<!--

';
try {
 	$xmlResult_EpisodesToWatch=$request->userRequest($ACCOUNT_USERNAME,"members/episodes/vf.xml");
 	var_dump($xmlResult_EpisodesToWatch);
 }
 catch (Exception $e)
 {
 	var_dump($e);
 }
 echo '-->


';*/
 try {
 	$xml=$request->userRequest($ACCOUNT_USERNAME,"members/watched/".$serie.'.xml',array('season' => $season,'episode' =>$episode));
 }
 catch (Exception $e)
 {
 	die('Erreur fatale: '.$e->message());
 }
 echo "<br />Requ&ecirc;te effectu&eacute;e avec succ&egrave;s."
 /*echo "
 <br />members/watched/".$serie.'.xml?season='.$season.'&episode='.$episode."
 <br />
 ";
 print_r($xml);
 echo "




 <!--";
 try {
 	$xmlResult_EpisodesToWatch=$request->userRequest($ACCOUNT_USERNAME,"members/episodes/vf.xml");
 }
 catch (Exception $e)
 {
 	var_dump($e);
 }
 echo "




";
 print_r($xmlResult_EpisodesToWatch);
 echo '
 
 -->';*/
?>