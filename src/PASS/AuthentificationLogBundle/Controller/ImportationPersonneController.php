<?php

// https://www.youtube.com/watch?v=5idECbKd_oo#t=677 15min

namespace PASS\AuthentificationLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PASS\AuthentificationLogBundle\Entity\ldapPersonne;
use Symfony\Component\HttpFoundation\Request;
use \PASS\AuthentificationLogBundle\Entity\Personne;


class ImportationPersonneController extends Controller {
    
    Private $dn;
    Private $Server;
    Private $port;
    function __construct() {
        
       
    }

    private function LdapConnection(){
        try{
         $server = $this->container->getParameter("ldap_server");
        
        $port = $this->container->getParameter("ldap_port");
       
            return  ldap_connect($server);
        }
        catch(Exception $ex)
        {
            
        }
            
    }
    
    public function ModificationPersonneLdapAction(Request $request,$username) {
        
        try{
            $dn  = $this->container->getParameter("ldap_dn");
            $filtre = "uid=ddevleeschauwer";
            $ds = $this->LdapConnection();
            $sr = ldap_search($ds, $dn, $filtre);
            $info = ldap_get_entries($ds, $sr);
            
            $rep = $this->getDoctrine()->getRepository("PASSAuthentificationLogBundle:Personne");
                         
                          $val = $rep->getUserLdap($info[0]['uid'][0]);
                         
                      $em = $this->getDoctrine()->getManager();
           
                   
                       if (isset($val[0]) && $val[0]->getId() != null) $userP = $val[0];
                        else $userP = new Personne();   
                           
                       
                      $userP->setMail($info[0]['mail'][0]);
       
                       $userP->setUsername($info[0]['uid'][0]);
                       $em->persist($userP);
             $em->flush();
            
            
            
            
        } catch (Exception $ex) {
            return 0;
        }
        $tab = $rep->getAllUserNoLdap();
            $tab2 = $rep->getAllUserLdap();
            return $this->render("PASSAuthentificationLogBundle:listing:listing.html.twig", array("titrePage" => "Listing utilisateur", "activite" => 'utilisateur',
                        "tab" => $tab, 'chemin' => "PASS_GestionUtilisateur", "ldap"=> $tab2, 'notification'=>1
            ));
    }

    public function importPersonneAction(Request $request) {

        
        $filtre = $this->container->getParameter("ldap_filtre");
        $this->dn  = $this->container->getParameter("ldap_dn");
        /*
         * 
         * gestion de connexion à ldap pour la recherche des utilisateurs
         */
        try {
           $ds = $this->LdapConnection();
            $sr = ldap_search($ds, $dn, $filtre);
            $info = ldap_get_entries($ds, $sr);

            $utilisateur = array();
            foreach ($info as $i) {

                if (isset($i['count'])) {

                    $display = null;
                    $mail = null;
                    if (isset($i["mail"])) {
                        $mail = $i["mail"][0];
                    }
                    if (isset($i["displayname"])) {
                        $display = $i["displayname"][0];
                    }
                    $utilisateur[$i["uid"][0]] =new ldapPersonne ($i["uid"][0], $display , $mail);
                }
            }
           
            
              $form =$this->createFormBuilder()
                
               ->add('utilisateur','choice',array('choices'=> $utilisateur,'multiple' => true,'required' => false, 'expanded'=>true ))
                -> add('importer','submit')
                      ->getForm() ;
                ;
                 $form->handleRequest($request);
                 if ($form->isSubmitted() && $form->isValid()) {
                     
                 $em = $this->getDoctrine()->getManager();
           
          
                   foreach ($form->getData() as $userldapTab)
                   { 
                      foreach($userldapTab as $userldap)
                       {
                          $rep = $this->getDoctrine()->getRepository("PASSAuthentificationLogBundle:Personne");
                         try{
                          $val = $rep->getUserLdap($userldap);
                         
                         }// dump($userldap);
                       catch(Exception $e){
                           
                       }
                   
                       if (isset($val[0]) && $val[0]->getId() != null) $userP = $val[0];
                        else $userP = new Personne();   
                           
                       
                       $userP->setLdap(True);
                       $userP->setActif(True);
                      $userP->setMail($utilisateur[$userldap]->getMail());
       
                       $userP->setUsername($userldap);
            
                      // $userP->addGroupe();
                       $em->persist($userP);
                      
                   }
                        $em->flush();
                        }
                        
                      
                     
                 }
            
            ldap_close($ds);
            
        } catch (Exception $e) {
            return $this->render('PASSGeneralLogBundle:erreur:ErreurLDAP.html.twig', Array("good" => $e));
        }

       
        return $this->render('PASSAuthentificationLogBundle:Import:importUser.html.twig', Array(
                               
                         "form" => $form->createView(),
                        
      ));
        
    }

}
