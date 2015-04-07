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
        /*$titre = new \PASS\GeneralLogBundle\Entity\ConfigurationMail();
        $titre = $titre->getTitre();
        if($this->getRequest()->getClientIp() === "127.0.0.1") {
        $message = \Swift_Message::newInstance()
        ->setSubject($this->renderView('PASSGestionLogBundle:mail:titreMail.html.twig',array("log"=> $listingId)))
        ->setFrom('GDL@pass.be')
        ->setTo(array("sortino@pass.be"))
       
        ->setBody($this->renderView('PASSGestionLogBundle:mail:mail.html.twig',array("log"=> $listingId)),'text/html')
    ;
    $this->get('mailer')->send($message);
    
         
        }*/
         
            return $this->render("PASSGestionLogBundle:mail:mail.html.twig",
                    array("corp" => "Logs serveur{{ name }}",
                        "log"=> $listingId
                         
                    
            ));
        
    }
}   