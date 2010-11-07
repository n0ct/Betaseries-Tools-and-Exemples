<?php
/*
 * Created on 22 oct. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: example_request.php
 */
 require_once '../../includes/betaseries/class_betaseries.php';
 require_once '../../metier/class_BetaSeriesRequest.php';
 require_once '../../config/config_betaseries.php';
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
 require_once '../../includes/betaseries/class_betaseries.php';
 require_once '../../metier/class_BetaSeriesRequest.php';
 require_once '../../config/config_betaseries.php';
 $ACCOUNT_USERS=unserialize(constant('ACCOUNT_USERS'));
 
 $request=BetaSeriesRequest::getInstance();
 $ACCOUNT_USERNAME = null;
 $ACCOUNT_PASSWORD = null;
 if (isset($_GET['login'])) $seekedLogin=$_GET['login'];
 else $seekedLogin=constant('SERVER_ACCOUNT');
 foreach ($ACCOUNT_USERS as $login => $password)
 {
 	if (trim($login) == $seekedLogin)
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
 $season=$_GET['season'];
 $episode=$_GET['episode'];
 echo '<br />Faites clic droit afficher la source pour plus d\'informations.





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


';
 $xml=$request->userRequest($ACCOUNT_USERNAME,"members/watched/".$serie.'.xml',array('season' => $season,'episode' =>$episode));
 echo "
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
?>-->