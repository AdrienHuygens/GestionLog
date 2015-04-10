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

class ConfigurationController extends Controller {
    /**
     * 
     * @Secure(roles="ROLE_CONFIGURATION_U,ROLE_CONFIGURATION_R, ROLE_ADMIN")
     */
    public function indexAction(Request $request) {
        $chemin = "";
        $Config = new \PASS\GeneralLogBundle\Entity\ConfigurationSql();
        $form = $this->createForm(new ConfigurationSqlType($this->get('security.context')), $Config);

        $form->handleRequest($request);
        if ($form->isValid()) {
             if (!$this->get('security.context')->isGranted('ROLE_CONFIGURATION_U') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
             
                    throw new AccessDeniedException('Modification Non dispognibe, vous n\'avez pas les droits ');
                  }
            $Config->Enregistrer();
        } else {
            // $Config->load();
            // $form->setData($Config);
        }
        

        return $this->render('PASSGeneralLogBundle:form:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Changer la configuration de la base de donné',
                    'chemin' => $chemin
        ));
    }

    /**
     * 
     * @Secure(roles="ROLE_CONFIGURATION_U, ROLE_CONFIGURATION_R, ROLE_ADMIN")
     */
    public function ldapAction(Request $request) {
        $chemin = "";
        $color= "";
        $Configs = new \PASS\GeneralLogBundle\Entity\ConfigurationLDAP();
        $Type =new ConfigurationLDAPType($this->get('security.context'));
        $form = $this->createForm($Type, $Configs);

        $form->handleRequest($request);
        if ($form->isValid()) {
           
            
                  if (!$this->get('security.context')->isGranted('ROLE_CONFIGURATION_U') && !$this->get('security.context')->isGranted('ROLE_CONFIGURATION_R')&& !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
             
                    throw new AccessDeniedException('Modification Non dispognibe, vous n\'avez pas les droits ');
                  } 
                  
            if ($form->get('Tester')->isClicked()) {
                 
                $db = ldap_connect($Configs->getLdapServer(), $Configs->getLdapPort()) ;
               
                if($db){
                    ldap_set_option($db, LDAP_OPT_PROTOCOL_VERSION, 3);
                    ldap_set_option($db, LDAP_OPT_REFERRALS, 0);
                    $test = @ldap_bind($db);
                   
                    if ($test){
                        dump($test);
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
             
                    throw new AccessDeniedException('Modification Non dispognibe, vous n\'avez pas les droits ');
                  }
                $Configs->Enregistrer();
            }
            
        } 
        
        return $this->render('PASSGeneralLogBundle:form:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Changer la configuration de la base de donné',
                    'chemin' => $chemin,
                    
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
}
