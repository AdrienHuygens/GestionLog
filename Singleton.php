<?php

/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * 
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 * Script d'execution permanante
 */
/*
set_time_limit(200);
set_time_limit(0);
ignore_user_abort(1);
*/
class Singleton
{

/**
*returns l'instance de la classe
*
*/
	private $Compteur =0;
	private static $instance = null;
	public static function getInstance(){
		
		if (!isset(static::$instance)){
		static::$instance=  new static;
		}
		return $instance;
	}
	private function __construct()
	{
		$this->Compteur = 0;
		$this->exect();	
	}
	 private function __clone()
	    {
	    }
	private function __wakeup()
    {
    }

	private function exect(){
		while(1){
    
   
		    $monFichier = fopen('/var/www/log/compteur.txt', 'a+');

		    
		    fputs($monFichier, "\n".$this->Compteur);        
		    fclose($monFichier);
		    $this->Compteur ++;
		    sleep(60);
    
		}
	}


}







?>

