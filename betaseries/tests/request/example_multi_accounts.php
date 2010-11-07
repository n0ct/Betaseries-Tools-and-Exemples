<?php
/*
 * Created on 22 oct. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: example_comparaison.php
 * 
 * L'objectif de ce code est de créer un tableau de la forme suivante et de l'afficher
 * 
 * [urlSerie] => [urlEpisode] => [] 
 * 
 * 
 */
 

 require_once '../../includes/betaseries/class_betaseries.php';
 require_once '../../config/config_betaseries.php';
 require_once '../../includes/exception/exception_handler.php';
 require_once '../../metier/class_BetaSeriesRequest.php';
 
 $ACCOUNT_USERS=unserialize(constant('ACCOUNT_USERS'));
 $request=BetaSeriesRequest::getInstance();
 $EpisodeArray=array();
 foreach ($ACCOUNT_USERS as $login => $password)
 {
 	echo "<br />Test Utilisateur $login ajout&eacute;: ";
 	try{
 		$request->addUser($login,$password);
 	}
 	catch(Exception $e)
 	{
		exception_handler($e);
 	}
 	echo '<br /> Test ContainsUser apr&egrave;s insertion: '; 
 	if($request->containsUser($login)) echo "ça marche";
 	else echo "erreur";
	echo "<br />";
 	$xmlResult_EpisodesToWatch=null;
 	$xmlSeries=null;
	try {
		$xmlResult_EpisodesToWatch=$request->userRequest($login,"members/episodes/vf.xml");
	}
	catch (Exception $e)
	{
		exception_handler($e); 	
	}
	$oldUrl = '';
	//Pour chaque épisode de chaque utilisateur
	foreach ($xmlResult_EpisodesToWatch->episodes->episode as $episode)
	{
		//On ne récupère que le premier épisode à voir de chaque série
		//On évite d'avoir deux fois les même épisode dans le tableau que l'ont génère
		if (!array_key_exists($episode->url . " " . $episode->episode,$EpisodeArray))
		{
			//on ajoute l'épisode au tableau avec l'utilisateur courrant
			$EpisodeArray[(string)$episode->url . " " . (string)$episode->episode]=array("episode" => $episode,"user" => $login);
		}
		else
		{
			//on ajoute l'utilisateur à l'épisode
			$EpisodeArray[(string)$episode->url . " " . (string)$episode->episode]["user"].= " ".$login;
		}
	}
 }
 echo "
<br /><br /><br />

";
//var_dump($EpisodeArray);
echo "

<br /><br /><br />
<ul>
";

$firstEpList=true;
for($i=count($ACCOUNT_USERS);$i>0;$i--)
{
	$firstEp=true;
	foreach ($EpisodeArray as $episode)
	{
		$EpisodeOwned=false;
		$arrayUsers=preg_split("/ /",$episode["user"]);
		$serie=$episode["episode"]->show;
		$nbUsers=count($arrayUsers);
		foreach ($arrayUsers as $login)
		{
			if ($login == constant('SERVER_ACCOUNT'))
			{
				$EpisodeOwned=true;
				$nbUsers--;
			}
		}
		if ($firstEp==true && $nbUsers == $i)
		{
			$firstEp=false;
			if ($firstEpList)
			{
				$firstEpList=false;
			}
			else echo "</ul></li>";
			echo '<li>'.$i.' utilisateurs:<ul>';
		}
		if ($nbUsers == $i)
		{
			echo '<li>';
			if ($EpisodeOwned)
			{
				echo '<img src="../../images/plot_green.gif" alt="Episode T&eacute;l&eacute;charg&eacute;."/>';
			}
			else 
			{
				echo ' <img src="../../images/plot_red.gif" alt="&Eacute;pisode non t&eacute;l&eacute;charg&eacute;.">'.
				'<a href="http://www.filestube.com/search.html?q='.$serie.'+'.$episode["episode"]->episode.'&select=All&hosting=3,27" target="_blank">' .
				'<img src="../../images/dl.jpg" width="13" alt="Cliquez i&ccedil;i pour t&eacute;l&eacute;charger l\'&eacute;pisode."/>' .
				'</a>';
			}
			echo ' - url: ';
			echo $serie.' | '.$episode["episode"]->episode.' | '.$episode['episode']->title.' | ';
			foreach ($arrayUsers as $login)
			{
				if ($login == constant('SERVER_ACCOUNT')) continue;
				else echo "$login ";
			}
			echo '-';
			$first=true;
			foreach($episode['episode']->subs->sub as $sub)
			{
				if ($first) $first=false;
				else echo '/';
				echo ' <a href="'.$sub->url.'" title="'.$sub->filename.'" target="_blank"><img src="../../images/srt.png" alt="'.$sub->filename.'<br/>provenance: '.$sub->source.'"/></a> ';
			}
			echo '</li>
			';	
		}
	}
}
echo "</ul>";
foreach ($ACCOUNT_USERS as $login => $pass)
{
	$request->delUser($login);
}
?>