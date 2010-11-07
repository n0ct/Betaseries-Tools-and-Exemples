<?php
/*
 * Created on 22 oct. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: exception_handler.php
 */
 
	function exception_handler($exception) { 
		ob_start(); 
		echo '<br/><br /><h1>Warning: exception detected</h1><br/><br /><!--
		';		
		print_r($GLOBALS);
		echo '
		'; 
		print_r($exception); 
		file_put_contents('exceptions.txt', ob_get_clean(). "\n",FILE_APPEND); 
		echo '
		-->';
	}
	set_exception_handler('exception_handler'); 
?>
