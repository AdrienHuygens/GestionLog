<?php

namespace PASS\GestionLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PASS\GestionLogBundle\Entity\GroupeOrdinateur;
use PASS\GestionLogBundle\Form\GroupeOrdinateurType;

use Symfony\Component\HttpFoundation\Request;

class EnvoyerMailController extends Controller
{
    public function indexAction(\PASS\GestionLogBundle\Entity\Systemevents $listingId){
        
       /* $message = \Swift_Message::newInstance()
        ->setSubject('Hello Email')
        ->setFrom('GDL@pass.be')
        ->setTo('huygens@pass.be')
        ->setBody("Bonjour")
    ;
    $this->get('mailer')->send($message);
        
    $monFichier = fopen('/var/www/log/compteur.txt', 'a+');

		    
		    fputs($monFichier, "\n 2");        
		    fclose($monFichier);
         
        */
    }
}   