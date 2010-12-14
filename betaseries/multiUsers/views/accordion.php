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
 $SERVER_ADDR=$_SERVER['HTTP_HOST'];
 require_once(dirname(__FILE__).'/../config/config_betaseries.php');
 require_once(dirname(__FILE__).'/../core/exception/exception_handler.php');
 require_once(dirname(__FILE__).'/../core/betaseries/class_BetaSeriesRequest.php');
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
/*echo "<!--";
var_dump($EpisodeArray);
echo " -->";*/
/*echo '
<table border="0">
<tr><td colspan="7"></td><td><a href="'.$_SERVER['HTTP_HOST'].'" onclick="betaseries(this.href);return false;">' .
		'<img  border="0" src="http://'.$SERVER_ADDR.'/donnees/betaseries/images/refresh.png" alt="Rafraichir la visualisation">' .
		'</a>' .
		'</td></tr>
';*/
echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>' .
	'<link rel="stylesheet" href="'.constant("FOLDER_PATH").'/css/accordion.css" type="text/css" media="screen" /> ' .
	'<section class="accordbk"><article class="horizontalaccordion">';
echo '<h4>&dArr; Bienvenue !</h4><ul>';
$firstIteration=true;
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
		$classEp=$episode["episode"]->url.'S'.$numSeason.'E'.$numEpisode;
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
			if (!$firstIteration)
			{
				echo '</table></div></li>';
			}
			if ($nbUsers==1)
			{
				echo '<li><h3>'.$currentUser.'</h3><div><table border="0">';
			}
			else
			{
				echo '<li><h3>'.$i.' personnes</h3><div><table border="0">';
			}
		}
		else if ($nbUsers==1 && $lastUser!=$currentUser)
		{
			echo '</table></div></li>';
			echo '<li><h3>'.$currentUser.'</h3><div><table border="0">';
		}
		//Si il n'y a qu'un seul utilisateur ET que l'utilisateur de l'episode précédant n'est pas le même que l'utilisateur courant
		echo '<tr>';
		if ($EpisodeOwned)
		{
			echo '<td colspan="2">' .
		         '<img border="0" src="http://'.$SERVER_ADDR.constant("FOLDER_PATH").'/images/plot_green.gif"' .
		         ' alt="Episode T&eacute;l&eacute;charg&eacute;."/></td>';
		}
		else 
		{
			echo '<td class="'.$classEp.'" id="'.$classEp.'">' .
				 '<a href="" onclick="javascript:watched(\''.$classEp.'\',\''.$episode["episode"]->url.'\',\''.$numSeason.'\',\''.$numEpisode.'\');return false;">' .
				 '<img  border="0" src="http://'.$SERVER_ADDR.constant("FOLDER_PATH").'/images/plot_red.png" alt="&Eacute;pisode non t&eacute;l&eacute;charg&eacute;."></a></td>'.
				 '<td><a href="http://www.filestube.com/search.html?q='.$episode["episode"]->show.'+'.$episode["episode"]->episode.'&select=All&hosting=3,27" target="_blank">' .
				 '<img src="http://'.$SERVER_ADDR.constant("FOLDER_PATH").'/images/dl.jpg"  border="0" width="13" alt="Cliquez i&ccedil;i pour t&eacute;l&eacute;charger l\'&eacute;pisode."/>' .
				 '</a></td>';
		}
		echo '<td>'.$episode["episode"]->show.'</td><td>'.$episode['episode']->episode.'</td><td>'.$episode['episode']->title.'</td><td>';
		$firstUser=true;
		if ($nbUsers>1) foreach ($arrayUsers as $login)
		{
			if ($login != constant('SERVER_ACCOUNT'))
			{
				if ($firstUser) $firstUser=false;
				else echo '<br />';
				echo $login;
			}
		}
		echo '</td><td>';
		$firstUser=true;
		foreach ($arrayUsers as $login)
		{
			if ($login != constant('SERVER_ACCOUNT'))
			{
				$classiduser=str_replace('.','',trim($login.$classEp));
				if ($firstUser) $firstUser=false;
				else echo '<br />';
				echo '<div id="'.$classiduser.'" class="'.$classiduser.'">'
				.'<a href="" onclick="javascript:userWatched(\''
				.$classiduser.'\',\''.$episode["episode"]->url.'\',\''.$numSeason.'\',\''.$numEpisode.'\',\''.$login.'\');'
				.'return false;">'
				.'<img border="0" src="http://'.$SERVER_ADDR.constant("FOLDER_PATH")
				.'/images/plot_red.png" alt="Marquer l\'&eacute;pisode comme vu"></a></div>';
			}
		}
		echo '</td><td>';
		foreach($episode['episode']->subs->sub as $sub)
		{
			echo ' <a href="javascript:subtitles(\''.$episode["episode"]->url.'\',\''.$numSeason.'\',\''.$numEpisode.'\');">' .
				 '<img border="0" src="http://'.$SERVER_ADDR.constant("FOLDER_PATH").'/images/srt.png" alt="download subtitles"/></a><div class="bsSubtitle" id="'.$episode["episode"]->url."S".$numSeason."E".$numEpisode.'"></div>';
			break;
		}
		echo '</td></tr>
		';
		$lastUser=$currentUser;
		$firstEp=false;
		$firstIteration=false;
	}
}
echo '</table></li></ul></article></section>';
foreach ($ACCOUNT_USERS as $login => $pass)
{
	$request->delUser($login);
}
?>
<script type="text/javascript">
	var Gepisode;
	var Gshow;
	var Gseason;
	var subaffiche=false;
	function watched(divName,show,season,episode)
	{
		$('#'+divName).html('<img border="0" src="http://<?=$_SERVER['HTTP_HOST'].constant("FOLDER_PATH")?>/images/plot_orange.gif" alt="Episode T&eacute;l&eacute;charg&eacute;."/>');
		
		$.ajax({
		  url: '<?=constant("FOLDER_PATH")?>/views/watched.php?show='+show+'&season='+season+'&episode='+episode,
		  success: function(data) {
		    $('#'+divName).html('<img border="0" src="http://<?=$_SERVER['HTTP_HOST'].constant("FOLDER_PATH")?>/images/plot_green.gif" alt="Episode T&eacute;l&eacute;charg&eacute;."/>');
		  }
		});
	}
	function userWatched(divName,show,season,episode,user)
	{
		$('#'+divName).html('<img border="0" src="http://<?=$_SERVER['HTTP_HOST'].constant("FOLDER_PATH")?>/images/plot_orange.gif" alt="Episode T&eacute;l&eacute;charg&eacute;."/>');
		$.ajax({
		  url: '<?=constant("FOLDER_PATH")?>/views/watched.php?show='+show+'&season='+season+'&episode='+episode+'&login='+user,
		  success: function(data) {
		    $('#'+divName).html('<img border="0" src="http://<?=$_SERVER['HTTP_HOST'].constant("FOLDER_PATH")?>/images/plot_green.gif" alt="Episode T&eacute;l&eacute;charg&eacute;."/>');
		  }
		});
	}
	function subtitles(Tshow,Tseason,Tepisode)
	{
		if(subaffiche)
		{
			$('#'+Gshow+'S'+Gseason+'E'+Gepisode).html(' ');
			subaffiche=false;
		}
		if (!(Tshow==Gshow && Tseason==Gseason && Tepisode==Gepisode))
		{
			subaffiche=true;
			Gepisode=Tepisode; 
			Gshow=Tshow;
			Gseason=Tseason;
			$.ajax({
				type: "GET",
	  			data: "show="+Tshow+"&season="+Tseason+"&episode="+Tepisode,
			  	url: '<?=constant("FOLDER_PATH")?>/views/subtitle.php',
			  	success: function(data) {
			    	$('#'+Tshow+'S'+Tseason+'E'+Tepisode).html(data);
			  	}
			});
		}
	}
</script><br /><br />