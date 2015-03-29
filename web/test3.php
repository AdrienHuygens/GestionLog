<?php

/* 


   * Copyright 2015 Version 1.0.0
   * Pour le Pass, projet gestion de log.
   * @author Huygens Adrien
   * contact adrien.huygens@gmail.com
 
 */

$monFichier = fopen('/var/www/log/compteur.txt', 'a+');

		    
		    fputs($monFichier, "\n 1");        
		    fclose($monFichier);
                    
                  /*  CREATE OR REPLACE FUNCTION process_emp_audit() RETURNS TRIGGER 
AS $emp_audit$
import requests
requests.get('http://log.pass.be/test3.php')
$emp_audit$ LANGUAGE plpythonu;

CREATE TRIGGER emp_audit
AFTER INSERT OR UPDATE OR DELETE ON systemevents
    FOR EACH ROW EXECUTE PROCEDURE process_emp_audit();
                   * 
                   * 
                   */