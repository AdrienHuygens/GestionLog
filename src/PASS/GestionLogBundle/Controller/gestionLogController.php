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
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Doctrine\ORM\Tools\Pagination\Paginator;


class gestionLogController extends Controller
{
    
    /**
     * 
     * @Secure(roles="ROLE_LOG_R, ROLE_ADMIN")
     */
    public function affichageLogAction(Request $request)
    {
        
       
        
         $repo = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\Systemevents");
         $var = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\config")->find(0);
        
         $host = array();
         $temp = $repo->getHost($var->getQuantiter());
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
         
         
         
         
        if ($this->get('session')->get('filtre') !== null){
          //$filtre = $session->get('filtre');   
             
         $filtre = $this->get('session')->get('filtre') ;
         
            /*if ($this->get('session')->get('pages') && $filtre->getNbPage !== $this->get('session')->get('pages')){
             $filtre->setNbPage($this->get('session')->get('pages'));
                 }*/
         //$filtre = new Filtre();
         //var_dump($this->get('session')->get('filtre'));
         }
         
         else{
             $page=40;
             if ($this->get('session')->get('pages')){
             $page = $this->get('session')->get('pages');
                 }
           $filtre = new Filtre($page);
         }
           //$filtre->addHost('test-debnet');
           //$filtre->addHost('test-ubublog');
           //====================
         
            $repo4 = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\serveur");
            $tmp = $repo4->findAll();
            $em =$this->getDoctrine()->getManager();
            $serveur = array();
            
            foreach($tmp as $s){
               
               $serveur[] = $s->getNom();
               $host[$s->getNom()] = $s->getNom();
            }
            
          foreach($host as  $variable){
             // dump($serveur );
              //dump(in_array($variable, $serveur));
              if (! in_array($variable, $serveur)){
                  
                  $serv = new\PASS\GestionLogBundle\Entity\serveur();
                  $serv->setNom($variable);
                  $em->persist($serv);
              }
              
          }
          
          $var->setQuantiter($repo->getMax()[0][1]);
           $em->persist($var);
          $em->flush();
          //==============================
          
           $form =$this->createFormBuilder($filtre)
                
               ->add('hosts','choice',array('choices'=>$host,'multiple' => true,'required' => false ))
               ->add('dates',new DateType())
               ->add('groupes','choice',array('choices'=>$groupe,'multiple' => true,'required' => false ))
               ->add('priority','choice',array('choices'=>$priority,'multiple' => true,'required' => false ))
               ->add('nbPage','choice',array('choices'=>array(20=>20,40=>40,100=>100,500=>500,1000=>1000),'multiple'=>false))
               ->add('Enregistrer','submit')
               ->add('up','button')
               ->add('Reset','button')
               ->getForm() ;
          $form->handleRequest($request);
          
                 
            $this->get('session')->set('pages',  $filtre->getNbPage());
            $this->get('session')->set('filtre',  $filtre);
            $this->get('session')->set('r',  'log');
            
        if ($form->isSubmitted()){
            
           
        
 
            return $this->redirectToRoute('PASS_AffichageLog');
            
        }
            
          $pagination = null;
        
           
           
          /*
            if(count($tab) !== 0){
               
                $paginator  = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $tab,
                   $request->query->get('page', 1),
                        
                    $filtre->getNbPage()
                );
                
            }
           */
          /*
           * ===================================================
           * Gestion de la pagination 
           * ==================================================
           * 
           */
            $page =  $request->query->get('page', 1);
            $maxArticles = $this->get('session')->get('pages');
             $articles_count = $this->getDoctrine()->getEntityManager()->getRepository("PASSGestionLogBundle:Systemevents")
                                    
                ->countTotal($filtre, $repo2);
             
             $paginations = array(
            'page' => $page,
            'route' => 'PASS_AffichageLog',
            'pages_count' => ceil($articles_count / $maxArticles),
            'route_params' => array()
        );  
             $tab = $repo->getAllLog($filtre, $repo2, $page, $maxArticles);
            
                 //  $articles = $this->getDoctrine()->getRepository("PASSGestionLogBundle:Systemevents")
                //->getList($page, $maxArticles);
            
            return $this->render("PASSGestionLogBundle:affichage:affichage.html.twig",
                    array("titrePage" => "Logs serveur",                       
                        "listing" =>  $tab,
                        "form" => $form->createView(),
                        "lien" => 'PASS_AffichageLog',
                        
                        'pagination' => $paginations
                       
                    
            ));
        
    }
     public function EraseAction($l)
    {
        $this->get('session')->set('filtre',  null);
      return $this->redirect($this->generateUrl($l));
            
    }
    
      
}

