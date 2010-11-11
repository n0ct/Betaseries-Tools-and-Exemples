<?php
/*
 * Created on 22 oct. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: class_request.php
 * 
 * Cette classe a pour objectif de:
 * -Permettre d'utiliser la classe bétaséries créée par Maxime Valette et de récupérer du xml
 * -De générer des exceptions en cas d'érreurs dans le xml récupéré
 * -De permettre de gérer une multitude d'utilisateurs différents et leurs tokens
 * -De permettre la mise en cache des requettes et des tokens utilisateurs sans pour autant utiliser une base de données
 * 
 * Elle constitue une couche d'abstraction entre vos scripts et la classe bétaséries de Maxime Valette.
 */
 //La classe de Maxime valette générant du xml après l'envoie d'une requette à l'API de bétaseries
 require_once(dirname(__FILE__)."/../includes/betaseries/class_betaseries.php");
 //une classe définissant des variables globales
 require_once(dirname(__FILE__)."/../config/config_betaseries.php");
 
 class BetaSeriesRequest{
 	
	/*-----------------------------------*/
 	/**  nom (sans l'extension) du fichier de cache contenant les utilisateurs enregistrés et leurs token)
 	 * 
 	 */
 	private static $CACHE_PATH_USER='users';
 	/*-----------------------------------*/
 	/** Instance de la classe Request (classe singleton)
 	 * 
 	 */
 	private static $instance;
 	
 	/*-----------------------------------*/
 	/** Instance de la classe BetaSeries créée par Maxime Valette
 	 * 
 	 */
 	private $connexion;
 	
 	/*-----------------------------------*/
 	/** Le tableau des utilisateurs qui se présente sous la forme suivante:
 	 * $usersArray=array('login' => array('password' => 'MyPassWord', 'token' => 'MyUserTokenOnTheAPI');
 	 */
 	private $usersArray=array();
 	
 	/*-----------------------------------*/
 	/** Le modele généré au retour d'une requete
 	 *
 	 */
 	private $modele;
 	/*-----------------------------------*/
 	/*--------Fonctions Statiques--------*/
 	/*-----------------------------------*/
 	/**
 	 *  getInstance récupère l'instance unique de cette classe
 	 *  (Design pattern singleton)
 	 */
 	public static function getInstance()
 	{
 		if (empty(self::$instance)) self::$instance=new BetaSeriesRequest();
 		return self::$instance;
 	}
 	
 	/*-----------------------------------*/
 	/*--------Fonctions Publiques--------*/
 	/*-----------------------------------*/
 	/** Fonction permettant d'ajouter un utilisateur, de récupérer son token pour pouvoir faire de futures requettes
 	 * @param $login le login de l'utilisateur
 	 * @param $password le password de l'utilisateur
 	 */
 	public function addUser($login,$password)
 	{
 		if (empty($login)) throw new Exception("Login is empty");
 		if (empty($password)) throw new Exception("Password is empty");
 		//TODO getUsersCache();
 		//Si le tableau ne contient pas déjà l'utilisateur
 		if (!$this->containsUser($login))
 		{
 			//On Récupère le token de l'utilisateur:':
 			$token=$this->getToken($login,$password);
 			if (empty($token)) throw new Exception("Le login ou le password de l'utilisateur est incorrect.'");
 			//On construit l'utilisateur et on l'ajoute au tableau
 			$this->usersArray[$login]=array("password" => $password,"token" => $token);
 			//TODO saveUsersCache();
 		}
 		else throw new Exception("User already exists");
 			
 	}
 	
 	/*-----------------------------------*/
 	/** Fonction permettant de supprimer un utilisateur et le token associé
 	 * @param $login le login de l'utilisateur'
 	 */
 	public function delUser($login)
 	{
 		if (empty($login)) throw new Exception("Login is empty");
 		//TODO getUsersCache();
 		if (!$this->containsUser($login)) throw new Exception("Login doesn't exists in stored users");
 		$this->connexion->set_token($this->usersArray[$login]['token']);
 		$this->connexion->send_request(constant("BETASERIES_API_ADRESS").'/members/destroy.xml');
 		$this->connexion->set_token('');
 		unset($this->usersArray[$login]);
 		//TODO saveUsersCache();
 	}
 	/** Fonction permettant de tester la validité d'un token pour un utilisateur donné.
 	 * Si le token n'est plus actif le token va être récupéré de nouveau puis mis à jour dans le userArray
 	 * Cette fonction est amené à être appellé uniquement lors de la récupération d'une erreur de code ???? après une requête.
 	 */
 	public function testToken($login)
 	{
 		
 	}
 	/*-----------------------------------*/
 	/** Vérifie que l'utilisateur est connu de la classe
 	 * (contenu dans le tableau usersArray)
 	 */
 	public function containsUser($login)
 	{
 		//TODO getUsersCache();
 		return array_key_exists((string)$login,$this->usersArray);
 	}
 	
 	/*-----------------------------------*/
 	/** Envoie une requete à l'api
 	 * 
 	 */
 	public function request($request,$optionsArray=null,$login=null)
 	{
 		if ($request == null) throw new Exception("Request is empty");
 		
 		//TODO
 		/* if (requestCacheExists($request)) $xml=getRequestCache($request,$login);
 		else */
 		
 		//Envoie une requette à l'api  et récupere un xml_simple_element
 		$xml = $this->connexion->send_request(constant("BETASERIES_API_ADRESS").$request,$optionsArray);
		
		//Récupère les erreurs et envoie l'exception correspondante si nécessaire
		
		// TODO
		/*try
		{
			$this->makeExceptions($xml);
		}
		catch (Exception $e)
		{
			throw $e;
		}*/
		
		//TODO saveRequestCache($request,$login,$xml);
		
		return $xml;
  	}
  	
  	/*-----------------------------------*/
  	/** Envoie une requette propre à un utilisateur.
  	 * @param login			le login de l'utilisateur
  	 * @param userRequest	la requete de l'utilisateur http://api.betaseries.com/apropos/api
   	 * @see method request
  	 */
 	public function userRequest($login,$userRequest,$optionsArray=null)
 	{
 		if (empty($userRequest)) throw new Exception("userRequest is empty");
		if (empty($login)) throw new Exception("login is empty");
		//TODO getUsersCache();
		if (empty($this->usersArray[$login])) throw new Exception("login is unknown. can't perform a user request.");
		$this->connexion->set_token($this->usersArray[$login]['token']);
		$xml=$this->request($userRequest,$optionsArray,$login);
 		$this->connexion->set_token(null);
		return $xml;
 	}
 	
 	
 	/*-----------------------------------*/
 	/*---------Fonction privées----------*/
 	/*-----------------------------------*/
 	
 	/*-----------------------------------*/
 	/** Constructeur de la classe
 	 *  
 	 */
 	private function __construct()
 	{
 		$this->connexion = new BetaSeries(constant('BETASERIES_API_KEY'));
 	}
 	
 	/*-----------------------------------*/
 	/** Génère les exceptions en fonction des codes d'erreurs retournés
 	 * 
 	 */
 	private function makeExceptions($xml)
 	{
 		$errors=null;
 		if (empty($xml)) throw new Exception("Aucun retour lors de l'envoie d'une requette à la base de données");
 		else foreach ($xml->errors->error as $err)
 		{
 			$errors.='Erreur API Betaseries ('.$err->code.')'.$err.'<br />';
 		}
 		if (empty($errors)) throw new Exception($err);
 	}

 	/*-----------------------------------*/
 	/** Récupère le token d'un utilisateur 
 	 * 
 	 */
 	private function getToken($login,$password)
 	{
 		$xml = $this->connexion->send_request(constant("BETASERIES_API_ADRESS").'/members/auth.xml',array('login' => $login , 'password' => md5($password)));
 		$token = (string)$xml->member->token;
 		return $token;
 	}
 	/*-----------------------------------*/
 	/* GESTION DU CACHE DES UTILISATEURS */
 	/*-----------------------------------*/
 	private function saveUsersCache()
 	{
 		if (empty($this->usersArray) || count($this->userArray) == 0) delUsersCache();
 		else saveCache(self::$CACHE_PATH_USER,serialize($this->usersArray));
 	}
	private function delUsersCache()
 	{
 		if (cacheExists(self::$CACHE_PATH_USER)) delCache(self::$CACHE_PATH_USER);
 	}
 	private function getUsersCache()
 	{
 		if (empty($this->usersArray) || (count($this->usersArray)<1))
 		{
 			if (cacheExists(self::$CACHE_PATH_USER)) $this->usersArray=unserialize(getCache(self::$CACHE_PATH_USER));
 			else $this->usersArray=array();
 		} 
 	}
 	/*-----------------------------------*/
 	/*-- GESTION DU CACHE D'UNE REQUETE -*/
 	/*-----------------------------------*/
 	private function saveRequestCache($request,$login,$xml)
 	{
 	    saveCache(makeRequestPath($request,$login),serialize($xml));
 	}
 	private function delRequestCache($request,$login)
 	{
 		delCache(makeRequestPath($request,$login));
 	}
 	private function requestCacheExists($request,$login)
 	{
 		return cacheExists(makeRequestPath($request,$login));
 	}
 	private function getRequestCache($request,$login)
 	{
 		$r=makeRequestPath($request,$login);
 		if (cacheExists($r)) return unserialize(getCache($r));
 		else return null;
 	}
 	private function makeRequestPath($request,$login)
 	{
 		$r="";
 		if ($login!=null) $r.= $login.'-';
 		$r.=$request;
 		return $r;
 	}
 	/*-----------------------------------*/
 	/*-------- GESTION DU CACHE ---------*/
 	/*-----------------------------------*/
 	//TODO
 	private function saveCache($file,$content)
 	{

 	}
 	//TODO
	private function delCache($file)
 	{
 		
 	}
 	//TODO
 	private function getCache($file)
 	{
 		
 	}
 	//TODO
	private function cacheExists($file)
 	{
 		
 	}
 }
?>
