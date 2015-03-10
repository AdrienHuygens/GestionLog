<?php

namespace PASS\GestionLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use PASS\GestionLogBundle\Entity\Filtre;
use PASS\GestionLogBundle\Form\DateType;

class statistiqueController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    
    public function affichageStatAction(Request $request)
    {
       
       
        $repo = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\Systemevents");
         $host = array();
         $temp = $repo->getHost();
         $repo2 = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\GroupeOrdinateur");
         $groupe= array();
         $temp2 = $repo2->getgroupe();
         
         /*$temp3 = new \PASS\GestionLogBundle\Entity\statistiquelog(new Filtre(), $this->getDoctrine());
         $temp3->stat();*/
         
         foreach($temp as $hote){
             $host[$hote['fromhost']]= $hote['fromhost'];
         }
         foreach($temp2 as $grp){
             $groupe[$grp['id']]= $grp['nom'];
         }
         
         
        if ($this->get('session')->get('filtre') !== null){
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
               ->add('Enregistrer','submit')
               ->add('up','button')
               ->add('Reset','button')
               ->getForm() ;
          $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {
                  
          
            $this->get('session')->set('filtre',  $filtre);
        }
         
                $stat = new \PASS\GestionLogBundle\Entity\statistiquelog($filtre,$this->getDoctrine());
                $resStat = $stat->stat();
            
           
             
              
            
            
            
            return $this->render("PASSGestionLogBundle:affichage:affichageStat.html.twig",
                    array("titrePage" => "Logs serveur",                       
                         "form" => $form->createView(),
                        'lien' => 'PASS_AffichageStat',
                        'stats' => $resStat
                       
                    
            ));
        
    }
}
