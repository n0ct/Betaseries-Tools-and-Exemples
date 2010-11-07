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
		if (trim((string)$episode->url) != trim((string)$oldUrl))
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
			//on sauvegarde l'url de l'épisode courrant pour l'itération suivante de la boucle
			$oldUrl=$episode->url;
		}
	}
 }
//var_dump($EpisodeArray);

echo "<html><body><table border=\"0\">
";
for($i=count($ACCOUNT_USERS);$i>0;$i--)
{
	$firstEp=true;
	foreach ($EpisodeArray as $episode)
	{
		$arrayUsers=preg_split("/ /",$episode["user"]);
		$nbUsers=count($arrayUsers);
		$EpisodeOwned=true;
		foreach ($arrayUsers as $login)
		{
			if ($login == constant('SERVER_ACCOUNT'))
			{
				$EpisodeOwned=false;
				$nbUsers--;
			}
		}
		if ($nbUsers != $i) continue;
		if ($firstEp)
		{
			$firstEp=false;
			echo '<tr><td colspan="7"></td></tr><tr><td colspan="7"><h3>'.$i.' utilisateurs:</h3></td></tr>';
		}
		if ($nbUsers == $i)
		{
			echo '<tr>';
			if ($EpisodeOwned)
			{
				echo '<td colspan="2"><img src="../../images/plot_green.gif" alt="Episode T&eacute;l&eacute;charg&eacute;."/></td>';
			}
			else 
			{
				echo '<td><img src="../../images/plot_red.gif" alt="&Eacute;pisode non t&eacute;l&eacute;charg&eacute;."></td>'.
				'<td><a href="http://www.filestube.com/search.html?q='.$episode["episode"]->show.'+'.$episode["episode"]->episode.'&select=All&hosting=3,27" target="_blank">' .
				'<img src="../../images/dl.jpg" width="13" alt="Cliquez i&ccedil;i pour t&eacute;l&eacute;charger l\'&eacute;pisode."/>' .
				'</a></td>';
			}
			echo '<td>'.$episode["episode"]->show.'</td><td>'.$episode['episode']->episode.'</td><td>'.$episode['episode']->title.'</td><td>';
			foreach ($arrayUsers as $login)
			{
				if ($login != constant('SERVER_ACCOUNT'))
				{
					echo $login.' ';
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
		}
	}
}
echo "</table></body></html>";
foreach ($ACCOUNT_USERS as $login => $pass)
{
	$request->delUser($login);
}
?>