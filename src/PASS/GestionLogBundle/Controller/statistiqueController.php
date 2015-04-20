<?php

namespace PASS\GestionLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use PASS\GestionLogBundle\Entity\Filtre;
use PASS\GestionLogBundle\Form\DateType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use PASS\GestionLogBundle\Entity\Statistique;
use PASS\GestionLogBundle\Entity\StatServeur;
use Exception;

class statistiqueController extends Controller
{
   
    /**
     * 
     * @Secure(roles="ROLE_STAT_R, ROLE_ADMIN")
     */
    public function affichageStatAction(Request $request)
    {
       
       
        $repo = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\Systemevents");
         $host = array();
         $temp = $repo->getHost();
         $repo2 = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\GroupeOrdinateur");
         $groupe= array();
         $temp2 = $repo2->getgroupe();
         $repo3 = $this->getDoctrine()->getRepository('PASS\GestionLogBundle\Entity\priority');
         $listNom = $repo3->findAll();
         
         /*$temp3 = new \PASS\GestionLogBundle\Entity\statistiquelog(new Filtre(), $this->getDoctrine());
         $temp3->stat();*/
         
         foreach($temp as $hote){
             $host[$hote['fromhost']]= $hote['fromhost'];
         }
         foreach($temp2 as $grp){
             $groupe[$grp['id']]= $grp['nom'];
         }
         
         
        if ($this->get('session')->get('filtre') !== null  ){
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
              
               ->add('groupes','choice',array('choices'=>$groupe,'multiple' => true,'required' => false ))
               ->add('dates',new DateType())
               ->add('Enregistrer','submit')
               ->add('up','button')
               ->add('Reset','button')
               ->getForm() ;
          $form->handleRequest($request);
            
       
          
            $this->get('session')->set('filtre',  $filtre);
            $this->get('session')->set('r', 'Stat');
        
         
                $stat = new \PASS\GestionLogBundle\Entity\Statistiquelog($filtre,$this->getDoctrine());
                
                $resStat = $stat->stat();
             
             
              
            
            
            
            return $this->render("PASSGestionLogBundle:affichage:affichageStat.html.twig",
                    array("titrePage" => "Logs serveur",                       
                         "form" => $form->createView(),
                        'lien' => 'PASS_AffichageStat',
                        'stats' => $resStat,
                      
                        
                        
                       
                    
            ));
        
    }
    
    public function RemoveStatAction()
    {
    try{
        $erro = null;
        $date = new \DateTime('NOW');
        $dat = new \PASS\GeneralLogBundle\Entity\ConfigurationServeur();
        $dat = $dat->getSrvDateLog();
        
        if($dat != 'avie'){
        $date->modify('-'.$dat);
        
       $rep = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\Systemevents");
       
        $event = $rep->getDateLog($date);
       
        $tab = array();
        $filtre = new Filtre();
        $em = $this->getDoctrine()->getManager();
        
        foreach($event as $log){
           
            $tab[$log['fromhost']][$log['dates']][] = array('couleur'=>$log['couleur'],'1'=>$log['1'],'id'=>$log['prioId'], 'nom'=>$log['nom']);
            
        }
        
          
          foreach($tab as $key =>$values){
              
              foreach($values as $keys =>$value){
                
              $stat = new StatServeur($key);
              $stat->generation($value,True);
              $statistique = new Statistique();
              $statistique->setServeur($key);
                $da = clone $date;
                $da->modify($keys.' days');
               
              $statistique->setDate($da);
              $statistique->setPriority($stat->getTableaux());
              //dump($stat->getTableaux());
           $em->persist($statistique);
              }
          }      
          
          $em->flush();
          $delete = $rep->deleteLog($date);
           $erro[] = array('vue' =>"PASSGeneralLogBundle:notification:BDDClear.html.twig");
           
        }
        else {
              $erro[] = array('vue' =>"PASSGeneralLogBundle:notification:BDDAVie.html.twig");
        }
    }
    catch(Exception $e)
    {
        $erro[] = array('vue' =>"PASSGeneralLogBundle:notification:BDDClearError.html.twig");
         }
    return $this->render('PASSGeneralLogBundle:form:ok.html.twig', Array(
                            "notification"=>$erro,
                            'titrePage' => 'Opération éffectué'));
    }
   
}
