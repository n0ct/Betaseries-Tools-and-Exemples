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
 	try{
 		$request->addUser($login,$password);
 	}
 	catch(Exception $e)
 	{
		exception_handler($e);
 	}
 	if(!$request->containsUser($login)) echo "<br />Erreur: l'utilisateur $login n'a pu &ecirc;tre ajout&eacute;. Login ou mot de passe incorrect";
 	$xmlResult_EpisodesToWatch=null;
 	$xmlSeries=null;
	try {
		if ($login == constant('SERVER_ACCOUNT')) $xmlResult_EpisodesToWatch=$request->userRequest($login,"members/episodes/vf.xml");
		else $xmlResult_EpisodesToWatch=$request->userRequest($login,"members/episodes/vf.xml",array("view" => "next"));
	}
	catch (Exception $e)
	{
		exception_handler($e); 	
	}
	$oldUrl = '';
	//Pour chaque épisode de chaque utilisateur
	foreach ($xmlResult_EpisodesToWatch->episodes->episode as $episode)
	{
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


echo "<html><body>
<!--

";
//var_dump($EpisodeArray);
echo "
-->
<table border=\"0\">
";
for($i=count($ACCOUNT_USERS);$i>0;$i--)
{
	$lastUser=null;
	$firstEp=true;
	foreach ($EpisodeArray as $episode)
	{
		$arrayUsers=preg_split("/ /",$episode["user"]);
		$seasonAndEpisode=preg_split('/[SE]/',$episode['episode']->episode);
		$numSeason=$seasonAndEpisode[1];
		$numEpisode=$seasonAndEpisode[2];
		//Ici on initialise le Nombre d'utilisateur (en comptant le serveur)
		$nbUsers=count($arrayUsers);
		$EpisodeOwned=true;
		$currentUser=null;
		foreach ($arrayUsers as $login)
		{
			if ($login == constant('SERVER_ACCOUNT'))
			{
				$EpisodeOwned=false;
				$nbUsers--;
			}
		}
		//Ici le $nbUsers ne comprend plus le serveur
		//Si il n'y a qu'un seul utilisateur on défini $currentUser au login de l'utilisateur courrant.	 
		if ($nbUser < 2) foreach ($arrayUsers as $login)
		{
			if ($login != constant('SERVER_ACCOUNT'))
			{
				$currentUser=$login;
			}
		}
		
		if ($nbUsers != $i) continue;
		if ($firstEp)
		{
			$firstEp=false;
			echo '<tr><td colspan="7"></td></tr><tr><td colspan="7"><h3>'.$i.' utilisateurs:</h3></td></tr>';
		}
		//Si il n'y a qu'un seul utilisateur ET que l'utilisateur de l'episode précédant n'est pas le même que l'utilisateur courant
		if ($nbUsers<2 && $lastUser!=$currentUser)
		{
			//On ajoute le nom de l'utilisateur comme titre.
			echo '<tr><td colspan="7"></td></tr><tr><td></td><td colspan="6"><h3>'.$currentUser.':</h3></td></tr>';
		}
		if ($nbUsers == $i)
		{
			echo '<tr>';
			if ($EpisodeOwned)
			{
				echo '<td colspan="2">' .
						'<img src="../../images/plot_green.gif" alt="Episode T&eacute;l&eacute;charg&eacute;."/></td>';
			}
			else 
			{
				echo '<td><a target="_blank" href="http://srv/donnees/betaseries/tests/request/example_watched.php?show='.$episode["episode"]->url.'&season='.$numSeason.'&episode='.$numEpisode.'">' .
						'<img src="../../images/plot_red.gif" alt="&Eacute;pisode non t&eacute;l&eacute;charg&eacute;."></a></td>'.
				'<td><a href="http://www.filestube.com/search.html?q='.$episode["episode"]->show.'+'.$episode["episode"]->episode.'&select=All&hosting=3,27" target="_blank">' .
				'<img src="../../images/dl.jpg" width="13" alt="Cliquez i&ccedil;i pour t&eacute;l&eacute;charger l\'&eacute;pisode."/>' .
				'</a></td>';
			}
			echo '<td>'.$episode["episode"]->show.'</td><td>'.$episode['episode']->episode.'</td><td>'.$episode['episode']->title.'</td><td>';
			foreach ($arrayUsers as $login)
			{
				if ($login != constant('SERVER_ACCOUNT'))
				{
					echo $login.' '.
					'<a target="_blank" href="http://srv/donnees/betaseries/tests/request/example_watched.php?show='.$episode["episode"]->url.'&season='.$numSeason.'&episode='.$numEpisode.'&login='.$login.'">' .
						'<img src="../../images/plot_red.gif" alt="Marquer l\'&eacute;pisode comme vu"></a>';
				}
			}
			echo '</td><td>';
			$first=true;
			foreach($episode['episode']->subs->sub as $sub)
			{
				if ($first) $first=false;
				else echo '&nbsp;&nbsp;';
				echo ' <a href="'.$sub->url.'" title="'.$sub->filename.'" target="_blank"><img src="../../images/srt.png" alt="'.$sub->filename.'<br/>provenance: '.$sub->source.'"/></a> ';
			}
			echo '</td></tr>
			';
			$lastUser=$currentUser;
		}
		
	}
}
echo "</table></body></html>";
foreach ($ACCOUNT_USERS as $login => $pass)
{
	$request->delUser($login);
}
?>