<?php

/**
 *
 * Classe pour utiliser l'API BetaSeries.
 *
 * Auteur : Maxime VALETTE
 * Contact : maxime@maximevalette.com
 *
 * @package BetaSeries
 *
 */

class BetaSeries {

	/**
	 *
	 * Clé développeur.
	 * @access private
	 * var string
	 *
	 */

	var $key = null;

	/**
	 *
	 * Token de l'utilisateur.
	 * @access private
	 * var string
	 *
	 */

	var $token = null; 

	/**
	 *
	 * Constructeur de la classe.
	 *
	 * @param string $key Clé développeur.
	 * @return class
	 *
	 */

	function __construct($key) {

		$this->key = $key;

	}

	/**
	 *
	 * Configure le token de l'utilisateur.
	 *
	 * @param string $token Token de l'utilisateur.
	 * @return true
	 *
	 */

	function set_token($token) {

		$this->token = $token;

		return true;

	}

	/**
	 *
	 * Envoie une requête sur l'API
	 *
	 * @param string $url URL de l'API à appeler.
	 * @param array $vars Tableau des variables éventuelles à renseigner.
	 * @return object Objet parsé par SimpleXML du retour de l'API.
	 *
	 */

	function send_request($url,$vars=null) {

		$url .= '?key='.$this->key.'&token='.$this->token;

		if (is_array($vars)) {

			foreach ($vars as $key => $value) {

				$url .= '&'.$key.'='.urlencode($value);

			}

		}

		$data = file_get_contents($url);

		$xml = simplexml_load_string($data);

		return $xml;

	}

}

?>