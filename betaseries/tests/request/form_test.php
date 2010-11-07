<?php
/*
 * Created on 7 nov. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: form_test.php
 */
 
 require_once '../../includes/betaseries/class_betaseries.php';
 require_once '../../config/config_betaseries.php';
 require_once '../../includes/exception/exception_handler.php';
 require_once '../../metier/class_BetaSeriesRequest.php';
 
 $ACCOUNT_USERS=unserialize(constant('ACCOUNT_USERS'));
 
?>
<html>
	<head>
		<title>BetaSeries API Request Testing</title>
	</head>
	<body>
		<h2>Composer une requ&ecirc;te: &agrave; l'API</h2>
		<form action="form_test.php" method="GET">
			<table border="0">
				<tr>
					<td>Requ&ecirc;te:</td>
					<td ><input type="text" class="text" name="request" value="members/episodes/vf.xml" size="30" maxlength="50" /></td>
				</tr>
				<tr>
					<td>Utilisateur:</td>
					<td >
						<select name="login" size="<?=count($ACCOUNT_USERS)?>">
							<option value="0">Aucun Utilisateur</option>
							<?php
							foreach ($ACCOUNT_USERS as $login => $password)
							{
	 							echo '<option value="'.$login.'">'.$login.'</option>';
	 						}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">Options:</td>
				</tr>
				<tr>
					<td><input type="text" class="text" name="key0" value="view" size="30" maxlength="50" /></td>
					<td><input type="text" class="text" name="value0" value="next" size="30" maxlength="50" /></td>
				</tr>
				<tr>
					<td><input type="text" class="text" name="key1" value="" size="30" maxlength="50" /></td>
					<td><input type="text" class="text" name="value1" value="" size="30" maxlength="50" /></td>
				</tr>
				<tr>
					<td><input type="text" class="text" name="key2" value="" size="30" maxlength="50" /></td>
					<td><input type="text" class="text" name="value2" value="" size="30" maxlength="50" /></td>
				</tr>
				<tr>
					<td><input type="text" class="text" name="key3" value="" size="30" maxlength="50" /></td>
					<td><input type="text" class="text" name="value3" value="" size="30" maxlength="50" /></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" class="submit button" name="name" value="Tester" /></td>
				</tr>
			</table>
		</form>
		<?php
		if (isset($_GET['request']) && !empty($_GET['request']))
		{
			$postrequest=trim($_GET['request']);
			$request=BetaSeriesRequest::getInstance();
			$postlogin=trim($_GET['login']);
			$arrayOptions=null;
			for ($i=0;$i<4;$i++)
			{
				$postkey=trim($_GET['key'.$i]);
				$postvalue=trim($_GET['value'.$i]);
				if (!empty($postkey) && !empty($postvalue))
				{
					$arrayOptions[$postkey]=$postvalue;
				}
			}
			echo '
			<h2>Retour de la requ&ecirc;te</h2>
			<br /><br/><br/><br />
			<p><textarea rows="50" cols="100">';
			echo 'R&eacute;capitulatif de la requ&ecirc;te:

';
			echo 'requ&ecirc;te: '.$postrequest.'
';
			echo 'login: '.$postlogin.'
';
			echo 'options: 
';
			var_dump($arrayOptions);		
			try{
				$request->addUser($login,$password);
			}
			catch(Exception $e)
			{
							echo '
Exception:
';		
				var_dump($e->getMessage());
			}
			if(!$request->containsUser($login)) echo "Erreur: l'utilisateur $login n'a pu &ecirc;tre ajout&eacute;. Login ou mot de passe incorrect";
			if (count($arrayOptions)==0) $xmlResult=$request->userRequest($login,"members/episodes/vf.xml");
			else $xmlResult=$request->userRequest($login,"members/episodes/vf.xml",$arrayOptions);
						echo '
Retour de la requ&ecirc;te:
';	
			var_dump($xmlResult);
			?>		
			</textarea></p>
		<?php
		}
		?>
	</body>
</html>