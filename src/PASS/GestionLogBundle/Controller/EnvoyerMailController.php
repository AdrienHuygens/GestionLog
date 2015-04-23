<?php

namespace PASS\GestionLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PASS\GestionLogBundle\Entity\GroupeOrdinateur;
use PASS\GestionLogBundle\Form\GroupeOrdinateurType;
use Symfony\Component\HttpFoundation\Request;
use PASS\GestionLogBundle\Entity\Systemevents;

class EnvoyerMailController extends Controller {

    public function indexAction() {
        /* $titre = new \PASS\GeneralLogBundle\Entity\ConfigurationMail();
          $titre = $titre->getTitre();
          if($this->getRequest()->getClientIp() === "127.0.0.1") {
          $message = \Swift_Message::newInstance()
          ->setSubject($this->renderView('PASSGestionLogBundle:mail:titreMail.html.twig',array("log"=> $listingId)))
          ->setFrom('GDL@pass.be')
          ->setTo(array("sortino@pass.be"))

          ->setBody($this->renderView('PASSGestionLogBundle:mail:mail.html.twig',array("log"=> $listingId)),'text/html')
          ;
          $this->get('mailer')->send($message);


          } */
        
        $rep = $this->getDoctrine()->getManager()->getRepository("PASSGestionLogBundle:GroupeOrdinateur");
        $rep3 = $this->getDoctrine()->getManager()->getRepository("PASSGestionLogBundle:mail");
        $mail = $rep3->findAll();
       
        $rep2 = $this->getDoctrine()->getManager()->getRepository("PASSGestionLogBundle:Systemevents");
        foreach ($mail as $m){
        $listing = $rep2->find($m->getId());
        
           $envoi =false;

        
        $value = $rep->getNameGroupe(true);
        
        $mail = array();
        foreach ($value as $groupeOrdi) {
            
            if ($groupeOrdi->getPriority() !== null && $listing !== null) {
                
                if ($groupeOrdi->getPriority()->getId() >= $listing->getPriority()->getId()) {
                    $envoi = true;
                    if (in_array($listing->getFromhost(), $groupeOrdi->getOrdinateurs()))
                        $mail = $groupeOrdi->getEmailUser($mail);
                }
            }
        }
        
         if ($envoi){
            
          $titre = new \PASS\GeneralLogBundle\Entity\ConfigurationMail();
          $titre = $titre->getTitre();
           
         // if($this->getRequest()->getClientIp() === "127.0.0.1") {
          $message = \Swift_Message::newInstance()
          ->setSubject($this->renderView('PASSGestionLogBundle:mail:titreMail.html.twig',array("log"=> $listing)))
          ->setFrom('GDL@pass.be')
          ->setTo($mail)

          ->setBody($this->renderView('PASSGestionLogBundle:mail:mail.html.twig',array("log"=> $listing)),'text/html')
          ;
          $this->get('mailer')->send($message);

        //}
        }
          $em = $this->container->get('doctrine')->getEntityManager();
         $em->remove($m);
         
        }
        $em->flush();
       
        
       
        return $this->render("PASSGestionLogBundle:mail:mail.html.twig", array("corp" => "Logs serveur{{ name }}",
                   "log" => $listing
        ));
    }

}
