<?php

/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */
namespace PASS\GestionLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use PASS\GestionLogBundle\Entity\Filtre;
use PASS\GestionLogBundle\Entity\Date;
use PASS\GestionLogBundle\Form\DateType;

use Symfony\Component\HttpFoundation\Session\Session;



class gestionLogController extends Controller
{
    /**
     * @Route("/affichagelog")
     * @Template()
     */
    
    public function affichageLogAction(Request $request)
    {
        
       
        
         $repo = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\Systemevents");
         $host = array();
         $temp = $repo->getHost();
         
         foreach($temp as $hote){
             $host[$hote['fromhost']]= $hote['fromhost'];
         }
             
         /*if ($this->get('session')->get('filtre') !== null){
         // $filtre = $session->get('filtre');   
             
         $filtre = $this->get('session')->get('filtre') ;
         
         
         }
         
         else{*/
           $filtre = new Filtre();
        // }
           //$filtre->addHost('test-debnet');
           //$filtre->addHost('test-ubublog');
          
           $form =$this->createFormBuilder($filtre)
                
               ->add('hosts','choice',array('choices'=>$host,'multiple' => true,'required' => false ))
               ->add('dates',new DateType())
               ->add('Enregistrer','submit')
               ->add('up','button')
               ->getForm() ;
          $form->handleRequest($request);
              
          
         
          
          //$this->get('session')->set('filtre',  $filtre);
        
          $pagination = null;
        
            $tab = $repo->getAllLog($filtre);
           
            if(count($tab) !== 0){
               
                $paginator  = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $tab,
                   $request->query->get('page', 1)/*page number*/,
                    30/*limit per page*/
                );
            }
           
               
           
            
            
            
            return $this->render("PASSGestionLogBundle:affichage:affichage.html.twig",
                    array("titrePage" => "Logs serveur",                       
                        "listing" =>  $pagination,
                        "form" => $form->createView()
                       
                    
            ));
        
    }
}

