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

class ConfigurationController extends Controller {

    public function indexAction(Request $request) {
        $chemin = "";
        $Config = new \PASS\GeneralLogBundle\Entity\ConfigurationSql();
        $form = $this->createForm(new ConfigurationSqlType(), $Config);

        $form->handleRequest($request);
        if ($form->isValid()) {

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

    public function ldapAction(Request $request) {
        $chemin = "";
        $Configs = new \PASS\GeneralLogBundle\Entity\ConfigurationLDAP();
        $form = $this->createForm(new ConfigurationLDAPType(), $Configs);

        $form->handleRequest($request);
        if ($form->isValid()) {

            $Configs->Enregistrer();
        } 
        
        return $this->render('PASSGeneralLogBundle:form:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Changer la configuration de la base de donné',
                    'chemin' => $chemin
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
    
 
    public function mailAction(Request $request)
    { 
        
        $repo = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\Systemevents");
        $log = $repo->find($repo->getMin()[0][1]);
     
        $Configs = new \PASS\GeneralLogBundle\Entity\ConfigurationMail();
        $Configs->verificationTwig();
       $form = $this->createForm(new ConfigurationMailType(), $Configs);

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
