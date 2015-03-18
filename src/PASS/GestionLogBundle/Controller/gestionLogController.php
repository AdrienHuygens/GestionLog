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
use PASS\GestionLogBundle\Form\GroupeType;

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
         $repo2 = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\GroupeOrdinateur");
         $groupe= array();
         $temp2 = $repo2->getgroupe();
         $repo3 = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\priority");
         $priority= array();
         $temp3 = $repo3->getPriority();
         
         /*$temp3 = new \PASS\GestionLogBundle\Entity\statistiquelog(new Filtre(), $this->getDoctrine());
         $temp3->stat();*/
         
         foreach($temp as $hote){
             $host[$hote['fromhost']]= $hote['fromhost'];
         }
         foreach($temp2 as $grp){
             $groupe[$grp['id']]= $grp['nom'];
         }
         foreach($temp3 as $prio){
             $priority[$prio['id']]= $prio['nom'];
         }
         
         
         
        if ($this->get('session')->get('filtre') !== null && $this->get('session')->get('r') != 'Stat'){
          //$filtre = $session->get('filtre');   
             
         $filtre = $this->get('session')->get('filtre') ;
         //$filtre = new Filtre();
         //var_dump($this->get('session')->get('filtre'));
         }
         
         else{
           $filtre = new Filtre();
         }
           //$filtre->addHost('test-debnet');
           //$filtre->addHost('test-ubublog');
          
           $form =$this->createFormBuilder($filtre)
                
               ->add('hosts','choice',array('choices'=>$host,'multiple' => true,'required' => false ))
               ->add('dates',new DateType())
               ->add('groupes','choice',array('choices'=>$groupe,'multiple' => true,'required' => false ))
               ->add('priority','choice',array('choices'=>$priority,'multiple' => true,'required' => false ))
               ->add('Enregistrer','submit')
               ->add('up','button')
               ->add('Reset','button')
               ->getForm() ;
          $form->handleRequest($request);
            
        
                  
          
            $this->get('session')->set('filtre',  $filtre);
            $this->get('session')->set('r',  'log');
        
          $pagination = null;
        
            $tab = $repo->getAllLog($filtre, $repo2);
           
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
                        "form" => $form->createView(),
                        "lien" => 'PASS_AffichageLog'
                       
                    
            ));
        
    }
     public function EraseAction($l)
    {
        $this->get('session')->set('filtre',  null);
      return $this->redirect($this->generateUrl($l));
            
    }
}

