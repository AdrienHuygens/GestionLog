<?php

/*


 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com

 */

namespace PASS\GeneralLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PASS\GeneralLogBundle\Entity\ConfigurationSql;
use PASS\GeneralLogBundle\Form\ConfigurationSqlType;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Security\Core\User\User;
use PASS\GeneralLogBundle\Entity\ConfigurationLDAP;
use PASS\GeneralLogBundle\Form\ConfigurationLDAPType;
use PASS\GeneralLogBundle\Form\ConfigurationMailType;
use PASS\GeneralLogBundle\Entity\ConfigurationMail;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use PASS\GestionLogBundle\Entity\Systemevents;
use PASS\GeneralLogBundle\Entity\Loggers;
use PASS\GeneralLogBundle\Entity\ConfigurationServeur;
use PASS\GeneralLogBundle\Form\ConfigurationServeurType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use mysqli;
use Exception;

class ConfigurationController extends Controller {
    
   
    
  
    private function testSql($Config){
        if ($Config->getDatabaseDriver() ==="pdo_pgsql"){
                    try{
                    if ($Config->getDatabasePort() != null)
                    $db = @pg_connect("host=".$Config->getDatabaseHost()." port=".$Config->getDatabasePort()." dbname=".$Config->getDatabaseName()."user=".$Config->getDatabaseUser()."password=".$Config->getDatabasePassword());
                    else $db = @pg_connect("host=".$Config->getDatabaseHost()." dbname=".$Config->getDatabaseName()." user=".$Config->getDatabaseUser()." password=".$Config->getDatabasePassword());
                    
                    if (pg_connection_status($db) === PGSQL_CONNECTION_OK){
                       
                        @pg_close($db);
                        return true;
                    } 
                    
                    
                    @pg_close($db);
                    }
                    catch(Exception $e){
                        
                    }
                    return false;                        
                }
        else if ($Config->getDatabaseDriver() ==="pdo_mysql"){
                                
                     $mysqli = @new mysqli($Config->getDatabaseHost(), $Config->getDatabaseUser(), $Config->getDatabasePassword(),$Config->getDatabaseName() , $Config->getDatabasePort());
                        if (!$mysqli->connect_errno) return true;
                     else return false;
                }
        else {
                    return false;
                }
    }
    
    
    
    /**
     * 
     * @Secure(roles="ROLE_CONFIGURATION_U,ROLE_CONFIGURATION_R, ROLE_ADMIN")
     */
    public function indexAction(Request $request) {
        
        $chemin = "";
        $Config = new \PASS\GeneralLogBundle\Entity\ConfigurationSql();
        $Type =new ConfigurationSqlType($this->get('security.context'));
        $form = $this->createForm($Type, $Config);
        $erro = array();
        $form->handleRequest($request);
        if ($form->isValid()) {
            
            if ($form->get('Tester')->isClicked()) {
                    
                if ($this->testSql($Config)){
                    
                     $Type->setColor("colorGreen");
                     
                }
                else{
                     $Type->setColor("colorRed");
                }
                   $form = $this->createForm($Type, $Config);
                
            }
           if ($form->get('Enregistrer')->isClicked()) {
              
             if (!$this->get('security.context')->isGranted('ROLE_CONFIGURATION_U') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
              new Loggers($this->getDoctrine()->getManager(), "Tentative de Modification de BDD sans avoir les droits"
                       . " par ".$this->get('security.context')->getToken()->getUser()->getusername()." Avec l'ip: ".$_SERVER['REMOTE_ADDR']);
                    throw new AccessDeniedException('Modification Non dispognibe, vous n\'avez pas les droits ');
                  }
          if ($this->testSql($Config)){
              
               new Loggers($this->getDoctrine()->getManager(), "Une modification a été réalisée sur la configuration de la base de données"
                       . " par ".$this->get('security.context')->getToken()->getUser()->getusername()." avec l'ip: ".$_SERVER['REMOTE_ADDR']);
            
               $Config->Enregistrer();
            $erro[] = array('vue' =>"PASSGeneralLogBundle:notification:connexionbddSucces.html.twig");
            }
            else{
                $Config = new \PASS\GeneralLogBundle\Entity\ConfigurationSql();
                 $form = $this->createForm($Type, $Config);
                 $erro[] = array('vue' =>"PASSGeneralLogBundle:notification:connexionbddError.html.twig");
            }
        } 
        }
    
        return $this->render('PASSGeneralLogBundle:form:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Changer la configuration de la base de données',
                    'chemin' => $chemin,
                    'notification'=>$erro
        ));
    }

    /**
     * 
     * @Secure(roles="ROLE_CONFIGURATION_U, ROLE_CONFIGURATION_R, ROLE_ADMIN")
     */
    public function ldapAction(Request $request) {
       try{
        $chemin = "";
        $color= "";
        $Configs = new \PASS\GeneralLogBundle\Entity\ConfigurationLDAP();
        $Type =new ConfigurationLDAPType($this->get('security.context'));
        $form = $this->createForm($Type, $Configs);
        $erro = array();

        $form->handleRequest($request);
        if ($form->isValid()) {
           
            
                  if (!$this->get('security.context')->isGranted('ROLE_CONFIGURATION_U') && !$this->get('security.context')->isGranted('ROLE_CONFIGURATION_R')&& !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
                       new Loggers($this->getDoctrine()->getManager(), "Tentative de Modification de LDAP sans avoir les droits"
                       . " par ".$this->get('security.context')->getToken()->getUser()->getusername()." Avec l'ip: ".$_SERVER['REMOTE_ADDR']);
                    throw new AccessDeniedException('Modification Non dispognibe, vous n\'avez pas les droits ');
                  } 
                  
            if ($form->get('Tester')->isClicked()) {
                 
                $db = ldap_connect($Configs->getLdapServer(), $Configs->getLdapPort()) ;
               
                if($db){
                    ldap_set_option($db, LDAP_OPT_PROTOCOL_VERSION, 3);
                    ldap_set_option($db, LDAP_OPT_REFERRALS, 0);
                    $test = @ldap_bind($db);
                   
                    if ($test){
                       
                        $Type->setColor("colorGreen");
                    }
                    else{
                        $Type->setColor("colorRed");
                    }
                    $form = $this->createForm($Type, $Configs);
                }
               
               
            }
             if ($form->get('Enregistrer')->isClicked()) {
                  if (!$this->get('security.context')->isGranted('ROLE_CONFIGURATION_U') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
                       new Loggers($this->getDoctrine()->getManager(), "Tentative de Modification de LDAP sans avoir les droits"
                       . " par ".$this->get('security.context')->getToken()->getUser()->getusername()." Avec l'ip: ".$_SERVER['REMOTE_ADDR']);
            
                    throw new AccessDeniedException('Modification Non dispognibe, vous n\'avez pas les droits ');
                  }
                   new Loggers($this->getDoctrine()->getManager(), "Une modification à été réalisé sur la configuration de LDAP"
                       . " par ".$this->get('security.context')->getToken()->getUser()->getusername()." Avec l'ip: ".$_SERVER['REMOTE_ADDR']);
            
                $Configs->Enregistrer();
                 $erro[] = array('vue' =>"PASSGeneralLogBundle:notification:connexionLdapSucces.html.twig");
            }
            
        } 
       }
 catch (Exception $e){
     $erro[] = array('vue' =>"PASSGeneralLogBundle:notification:connexionLdapError.html.twig");
 }
        return $this->render('PASSGeneralLogBundle:form:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Changer la configuration LDAP',
                    'chemin' => $chemin,
                    'notification' => $erro
                    
        ));
    }
    
    public function mdpAction(Request $request) {
        $chemin = "";
        $mdp="";
        $user = new User("AdminM","test");
         $form =$this->createFormBuilder()
         ->add('mot_de_pass', 'text')
                ->add('Generate','submit')
                ->getForm() ;
                ;
         
        $form->handleRequest($request);
        
        
        
        
        if ($form->isValid()) {
            $factory = $this->get('security.encoder_factory');
           $user = new User('AdminM', "test");
           $encoder = $factory->getEncoder($user);
           $mdp = $encoder->encodePassword('test', $user->getSalt());
        
           
        } 
          
        return $this->render('PASSGeneralLogBundle:form:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Changer la configuration de la base de donné',
                    'chemin' => $chemin,
                    'mdp'=> $mdp
        ));
    }
    
    /**
     * 
     * @Secure(roles="ROLE_CONFIGURATION_U,ROLE_CONFIGURATION_R, ROLE_ADMIN")
     */
    public function mailAction(Request $request)
    { 
        
        $repo = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\Systemevents");
        $log = $repo->find($repo->getMin()[0][1]);
     
        $Configs = new \PASS\GeneralLogBundle\Entity\ConfigurationMail();
        $Configs->verificationTwig();
       $form = $this->createForm(new ConfigurationMailType($this->get('security.context')), $Configs);

        $form->handleRequest($request);
        if ($form->isValid()) {
         if ($form->get('Prévisualiser')->isClicked()) {
             $Configs->Previsualisation();
             return $this->render('PASSGeneralLogBundle:form:prevuMail.html.twig', Array(
                    "form" => $form->createView(),              
                    "html"=> $Configs->getBody(),
                    "log"=>$log,
                     "css"=>$Configs->getCss(),
                     "erreur"=> $Configs->verificationTwig($Configs->getBody()),
                     "erreurTitre"=>$Configs->verificationTwig($Configs->getTitre())
                   
                    
                    
        ));
            }
            if ($form->get('Enregistrer')->isClicked()) {
                 if (!$this->get('security.context')->isGranted('ROLE_CONFIGURATION_U') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
             
                    throw new AccessDeniedException('Modification Non dispognibe, vous n\'avez pas les droits ');
                  }
             $Configs->Enregistrer();
            }
           // $Configs->Enregistrer();
        } 
        return $this->render('PASSGeneralLogBundle:form:formMail.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Changer le message de l\'email',
                    "html"=> $Configs->getBody(),
                    "log"=>$log
                   
                    
                    
        ));
    }
    
    /**
     * 
     * @Secure(roles="ROLE_CONFIGURATION_U,ROLE_CONFIGURATION_R, ROLE_ADMIN")
     */
    public function ServeurAction(Request $request)
    { 
        
       
     $notification = null;
     
        $Configs = new \PASS\GeneralLogBundle\Entity\ConfigurationServeur();
       
       $form = $this->createForm(new ConfigurationServeurType($this->get('security.context')), $Configs);

        $form->handleRequest($request);
        if ($form->isValid()) {
         
           
          
                 if (!$this->get('security.context')->isGranted('ROLE_CONFIGURATION_U') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
             
                    throw new AccessDeniedException('Modification Non dispognibe, vous n\'avez pas les droits ');
                  }
                  
                  if ($form->get('Vider')->isClicked()) {
                        //return $this->redirectToRoute('PASS_statRemove2', array(), 301);
                        return new RedirectResponse($this->generateUrl('PASS_statRemove2'));
                  }
                  
             $Configs->Enregistrer();
            $notification[] =array('vue' =>"PASSGeneralLogBundle:notification:ServeurConfig.html.twig");
           // $Configs->Enregistrer();
        } 
        return $this->render('PASSGeneralLogBundle:form:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Modification serveur',
                          
                    "notification"=> $notification
                    
                    
        ));
    }
}
