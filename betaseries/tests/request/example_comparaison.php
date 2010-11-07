<?php
/*
 * Created on 22 oct. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: example_comparaison.php
 */
 require_once '../../includes/betaseries/class_betaseries.php';
 require_once '../../config/config_betaseries.php';
 require_once '../../includes/exception/exception_handler.php';
 require_once '../../metier/class_BetaSeriesRequest.php';
 $ACCOUNT_USERNAME = constant('ACCOUNT_USERNAME');
 $ACCOUNT_PASSWORD = constant('ACCOUNT_PASSWORD');
 $request=BetaSeriesRequest::getInstance();
 echo "<br />Test Utilisateur ajout&eacute;: "/
 $request->addUser($ACCOUNT_USERNAME,$ACCOUNT_PASSWORD);
 echo '<br /> Test ContainsUser apr&egrave;s insertion:'; 
 echo $request->containsUser($ACCOUNT_USERNAME)."<br />";
 $xmlResult_EpisodesToWatch=null;
 try {
 	$xmlResult_EpisodesToWatch=$request->userRequest($ACCOUNT_USERNAME,"members/episodes/vf.xml");
 }
 catch (Exception $e)
 {
 	exception_handler($e);
 }
 $xmlSeries=$request->userRequest($ACCOUNT_USERNAME,"members/infos.xml");
 echo "
 <br />On parcours les séries:
 <br /><ul>
 ";
 
 foreach ($xmlSeries->member->shows->show AS $show)
 {
 	$xmlEp=$request->userRequest($ACCOUNT_USERNAME,'shows/episodes/'.$show->url.'.xml');
 	$nbSeason=sizeof($xmlEp->seasons->season);
 	echo "<li>==>Nombre de saisons: ".$nbSeason." </li>";
 	echo "

	<li><u>$show->title:$show->url</u><ul>

	"; 	
 	
 	
 	
 	//Pour chaque saison
 	foreach ($xmlEp->seasons->season AS $season)
 	{
 		/*nous allons afficher uniquement la derniere saison:
 		if	 ($season->number == $nbSeason)
  		{*/
 			echo "
			<li>Saison".$season->number."<ul>
			";
			//Pour chaque épisode
 			foreach($season->episodes->episode AS $episode)
 			{
 				/*$a=intval($episode->episode);
 				$b=sizeof($season->episodes->episode);
 				if ($a == $b)
 				{*/
	 				echo "
					<li>";
					$toSee=episodeEquals($show,$episode,$xmlResult_EpisodesToWatch);
					/*die("</li></ul></li></ul></li></ul>");*/
	 				
					if ($toSee) echo "<span style=\"color: red;\">";
	 				echo $episode->number.":".$episode->title;
	 				if ($toSee) echo("</span>");
					echo "</li>";
 				//}
 			}
			echo "
			</ul></li>";
 		//}
 	}
 	echo "
		</ul></li>";
 }
 echo "
 </ul>";

 function episodeEquals($show,$xmlResult_Episode,$xmlResult_EpisodesToWatch)
 {
 	/*echo "A voir==episode listé
 	<br />";*/
	foreach ($xmlResult_EpisodesToWatch->episodes->episode AS $episodeToWatch)
	{
		
		/*echo "$episodeToWatch->url==$show->url$xmlResult_Episode->number
		<br/>";*/
		if ($show->url.$xmlResult_Episode->number == $episodeToWatch->url) return true;
	}
	return false;
 } 
?>