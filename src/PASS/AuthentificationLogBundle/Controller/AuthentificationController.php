<?php

// https://www.youtube.com/watch?v=5idECbKd_oo#t=677 15min

namespace PASS\AuthentificationLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use PASS\AuthentificationLogBundle\Entity\Groupe;
use PASS\AuthentificationLogBundle\Form\GroupeType;
use PASS\AuthentificationLogBundle\Entity\Personne;
use PASS\AuthentificationLogBundle\Form\PersonneType;
use PASS\AuthentificationLogBundle\Form\editPersonneType;
use PASS\AuthentificationLogBundle\Form\changeMDPType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use PASS\AuthentificationLogBundle\Form\groupeRoleType;
use PASS\AuthentificationLogBundle\Form\roleType;

class AuthentificationController extends Controller {

   

    
     /**
     * 
     * @Secure(roles="ROLE_GROUPE_U,ROLE_GROUPE_C, ROLE_ADMIN")
     */
    public function groupeAddAction(Request $request,  Groupe $listingId=null) {
        $chemin = null;

        if ($listingId !== null) {
             if (!$this->get('security.context')->isGranted('ROLE_GROUPE_U') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
             
                    throw new AccessDeniedException('Au gestionnaire de groupe avec modification ');
                  }
                  $em = $this->getDoctrine()->getRepository('PASSAuthentificationLogBundle:Groupe');

            $groupe = $em->findOneById($listingId);
            $titre = "Modifier un groupe local";
            $chemin= null;
            if ($this->get('security.context')->isGranted('ROLE_GROUPE_D') || $this->get('security.context')->isGranted('ROLE_ADMIN')){
            $chemin = $this->generateUrl('PASS_SupprimerGroupe', array('groupeId' => $listingId->getId()));
            }
            
        } else {
              if (!$this->get('security.context')->isGranted('ROLE_GROUPE_C') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
             
                    throw new AccessDeniedException('Au gestionnaire de groupe avec création');
                  }
            $groupe = new Groupe();
            $groupe->setLdap(False);
            $groupe->setActif(True);

            
            $titre = "Ajouter un Groupe local";
        }

        $form = $this->createForm(new GroupeType($this->getDoctrine()->getRepository('PASSAuthentificationLogBundle:Role')), $groupe);

       // if ($listingId !== 0) {
           // $form->add('actif');
       // }
        $form->add('save', 'submit');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //var_dump(str_replace('<br>', "\n", $groupe->getDescription()));


            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();
            
            $token = new UsernamePasswordToken(
           $this->getUser(),
            null,
            'ldap',
            $this->getUser()->getAllRoles()
                  //array("ROLES_ADMIN")
            );
            $this->container->get('security.context')->setToken($token);    
            
            //$this->addFlash('success', '')
            ///$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            //return $this->redirect($this->generateUrl('', array('id' => $advert->getId())));
            if ($listingId === null) {
                return $this->render('PASSAuthentificationLogBundle:authentification:ok.html.twig', Array(
                            "good" => "Groupe bien créé.",
                            'titrePage' => 'Opération éffectué'
                ));
            } else {
                return $this->redirect($this->generateUrl('PASS_GestionGroupe', array('listingId' => $listingId->getId())));
            }
        }

        return $this->render('PASSAuthentificationLogBundle:authentification:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => $titre,
                    'chemin' => $chemin
        ));
    }
    /**
     * 
     * @Secure(roles="ROLE_USER_C, ROLE_ADMIN")
     */
    public function utilisateurAddAction(Request $request) {

        $chemin = null;

        $user = new Personne();
        $user->setActif(True);
        $user->setLdap(False);
        $ems = $this->getDoctrine() ->getRepository('PASSAuthentificationLogBundle:Groupe');
        $ems2 = $this->getDoctrine() ->getRepository('PASSAuthentificationLogBundle:Role');
        $groupe = $ems->findBy(array("nom"=>'Default'));
       

        $form = $this->createForm(new PersonneType( $ems,$ems2 ), $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $user->addGroupe($groupe[0]);
            $user->setMdp($encoder->encodePassword($user->getMdp(), $user->getSalt()));


            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
           
            
            
            return $this->render('PASSAuthentificationLogBundle:authentification:ok.html.twig', Array(
                        "good" => "utilisateur d'utilisateur bien créé.",
                        'titrePage' => 'Opération éffectué',
            ));
        }
        return $this->render('PASSAuthentificationLogBundle:authentification:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Rajouter un utilisateur local',
                    'chemin' => $chemin
        ));
    }

   

    /*
     * function pour la vérification de connexion.
     */
    public function loginAction(Request $request) {
        //var_dump($request);
        $session = $request->getSession();
// get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('PASSAuthentificationLogBundle:authentification:login.html.twig', array(
// last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
        ));
    }
    
    /**
     * 
     * @Secure(roles="ROLE_DEFAULT, ROLE_ADMIN, ROLE_MEMORY")
     */
    public function okAction() {
        $em = $this->getDoctrine()->getManager();

        if (!$this->get('security.context')->isGranted('ROLE_MEMORY')){
          $this->getUser()->setDernierConnexion(new \DateTime());
          $em->persist($this->getUser());
        $em->flush(); }
         // dump($this->getUser()->getRoles());
        return $this->render("PASSAuthentificationLogBundle:authentification:ok.html.twig", Array(
                    'titrePage' => 'Connexion effectué',
                    'good' => null));
    }
    
    /**
     * 
     * @Secure(roles="ROLE_USER_C,ROLE_USER_R, ROLE_ADMIN, ROLE_MEMORY")
     */
    public function utilisateurListingAction($listingId) {
       
        if ($listingId != 0) {

            $utilisateur = $this->getDoctrine()->getRepository("PASSAuthentificationLogBundle:Personne")->find($listingId);

            return $this->render('PASSAuthentificationLogBundle:listing:recapitulatif.html.twig', Array(
                        'titrePage' => 'Gestion d\'un utilisateur', 'groupe' => $utilisateur->resumer(), 'nom' => $utilisateur->getUsername(),
                        'activiter' => " Information sur l'utilisateur", 'lien' => 'PASS_ModificationUtilisateur', 'id' => $listingId,
                        'activite' => "utilisateur"
            ));
        } else {
            $repoJeune = $this->getDoctrine()->getRepository("PASS\AuthentificationLogBundle\Entity\Personne");
            $tab = $repoJeune->getAllUserNoLdap();
            $tab2 = $repoJeune->getAllUserLdap();
            return $this->render("PASSAuthentificationLogBundle:listing:listing.html.twig", array("titrePage" => "Listing utilisateur", "activite" => 'utilisateur',
                        "tab" => $tab, 'chemin' => "PASS_GestionUtilisateur", "ldap" => $tab2,
            ));
        }
    }
    /**
     * 
     * @Secure(roles="ROLE_GROUPE_R, ROLE_ADMIN")
     */
    public function groupeListingAction($listingId) {
        if ($listingId != 0) {

            $groupe = $this->getDoctrine()->getRepository("PASSAuthentificationLogBundle:Groupe")->find($listingId);

            return $this->render('PASSAuthentificationLogBundle:listing:recapitulatif.html.twig', Array(
                        'titrePage' => 'Gestion d\'un groupe', 'groupe' => $groupe->resumer(), 'nom' => $groupe->getNom(),
                        'activiter' => " Information sur le groupe", 'lien' => 'PASS_ModificationGroupe', 'id' => $listingId,
                        'activite' => "groupe"
            ));
        } else {

            $repoJeune = $this->getDoctrine()->getRepository("PASS\AuthentificationLogBundle\Entity\Groupe");
            $tab = $repoJeune->getAllGroupe();

            return $this->render("PASSAuthentificationLogBundle:listing:listing.html.twig", array("titrePage" => "Listing des groupes", "activite" => 'groupe', "tab" => $tab, 'chemin' => "PASS_GestionGroupe",));
        }
    }
    /**
     * 
     * @Secure(roles="ROLE_USER_U, ROLE_ADMIN,  ROLE_MEMORY")
     */
    public function utilisateurModificationAction(Request $request, Personne $listingId) {
  
        return $this->modifUser($request, $listingId);
    }
    /**
     * 
     * @Secure(roles="ROLE_USER_D, ROLE_ADMIN")
     */
    public function utilisateurSupprimerAction(Personne $personneId) {

        $em = $this->container->get('doctrine')->getEntityManager();


        //$personne = $em->find('PASSAuthentificationLogBundle:Personne', $id);
        $em->remove($personneId);
        $em->flush();
        return $this->redirect($this->generateUrl('PASS_GestionUtilisateur'));
    }
    /**
     * 
     * @Secure(roles="ROLE_GROUPE_D, ROLE_ADMIN")
     */
    public function groupeSupprimerAction(Groupe $groupeId) {

        $em = $this->container->get('doctrine')->getEntityManager();


        //$personne = $em->find('PASSAuthentificationLogBundle:Personne', $id);
        $em->remove($groupeId);
        $em->flush();
        return $this->redirect($this->generateUrl('PASS_GestionGroupe'));
    }
    
    public function changeMDPAction(Request $request, Personne $personneId) {
        
         if ((!$this->get('security.context')->isGranted('ROLE_USER_U') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) && $personneId->getUsername() !== $this->getUser()->getUsername() &&!$this->get('security.context')->isGranted('ROLE_MEMORY') 
                          || ($this->get('security.context')->isGranted('ROLE_MEMORY') && $personneId->getSuprimable() )) {
             
                    throw new AccessDeniedException('Vous avez pas acces à cette partie.');
                  }
        $form = $this->createForm(new changeMDPType(), $personneId);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($personneId);

            $personneId->setMdp($encoder->encodePassword($personneId->getMdp(), $personneId->getSalt()));


            $em = $this->getDoctrine()->getManager();
            $em->persist($personneId);
            $em->flush();

            return $this->render('PASSAuthentificationLogBundle:authentification:ok.html.twig', Array(
                        "good" => "Modification mot de passe.",
                        'titrePage' => 'Opération éffectué',
            ));
        }


        return $this->render('PASSAuthentificationLogBundle:authentification:changeMdp.html.twig', array(
                    'form' => $form->createView(),
                    'id' => $personneId->getId()
        ));
    }
    
    
    
    
    public function changeMonMDPAction(Request $request) {
        
                  $personneId = $this->getDoctrine()->getRepository("PASS\AuthentificationLogBundle\Entity\Personne")->find($this->getUser()->getId());
        
         if ((!$this->get('security.context')->isGranted('ROLE_USER_U') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) && $personneId->getUsername() !== $this->getUser()->getUsername() &&!$this->get('security.context')->isGranted('ROLE_MEMORY') 
                          || ($this->get('security.context')->isGranted('ROLE_MEMORY') && $personneId->getSuprimable() )) {
             
                    throw new AccessDeniedException('Vous avez pas acces à cette partie.');
                  }
                          $form = $this->createForm(new changeMDPType(), $personneId);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($personneId);

            $personneId->setMdp($encoder->encodePassword($personneId->getMdp(), $personneId->getSalt()));


            $em = $this->getDoctrine()->getManager();
            $em->persist($personneId);
            $em->flush();

            return $this->render('PASSAuthentificationLogBundle:authentification:ok.html.twig', Array(
                        "good" => "Modification mot de passe.",
                        'titrePage' => 'Opération éffectué',
            ));
        }


        return $this->render('PASSAuthentificationLogBundle:authentification:changeMdp.html.twig', array(
                    'form' => $form->createView(),
                    'id' => $personneId->getId()
        ));
    }
    
    
    
    public function MonCompteAction(Request $request) {
  
         
        $personne =  $this->getDoctrine()->getRepository("PASS\AuthentificationLogBundle\Entity\Personne")->find($this->getUser()->getId());
        return $this->modifUser($request, $personne);
    
    }
    
    private function modifUser(Request $request,Personne $personne){
        if (!$personne->getSuprimable())
        {    
            $form = $this->createForm(new \PASS\AuthentificationLogBundle\Form\editPersonneNonSupType($this->getDoctrine() ->getRepository('PASSAuthentificationLogBundle:Groupe'), $this->getDoctrine()->getRepository('PASSAuthentificationLogBundle:Role'), $this->get('security.context')), $personne);
        }
        else
        {
             $form = $this->createForm(new editPersonneType($this->getDoctrine() ->getRepository('PASSAuthentificationLogBundle:Groupe'), $this->getDoctrine()->getRepository('PASSAuthentificationLogBundle:Role')), $personne);
        }
        
         $groupetmp = clone  $personne->getGroupes();
         
         $roletmp = clone  $personne->getRoles();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
          
            
            //$user->setMdp($encoder->encodePassword($personne->getMdp(), $personne->getSalt()));
            foreach($groupetmp as $groupe){
                if(! $groupe->getSupprimable()) $personne->addGroupe($groupe);
            }
            foreach($roletmp as $role){
                if($role->getType() =="") $personne->addRole($role);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($personne);
           
            $em->flush();
             $token = new UsernamePasswordToken(
           $this->getUser(),
            null,
            'ldap',
            $this->getUser()->getAllRoles()
                  //array("ROLES_ADMIN")
            );
            $this->container->get('security.context')->setToken($token);    
           
            if ( !$this->get('security.context')->isGranted('ROLE_USER_U') && !$this->get('security.context')->isGranted('ROLE_ADMIN')){
                return $this->render("PASSAuthentificationLogBundle:authentification:ok.html.twig", Array(
                    'titrePage' => 'Modification de votre compte',
                    'good' =>" modification de votre profil réalisé"));
            }
            else return $this->redirect($this->generateUrl('PASS_GestionUtilisateur', array('listingId' => $personne->getId())));
        }
        if(!$personne->getSuprimable() && !$this->get('security.context')->isGranted('ROLE_MEMORY')){
            return $this->render('PASSAuthentificationLogBundle:authentification:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Modifier un utilisateur local',
                    'id' => $personne->getId(),
                    'suprimable' => $personne->getSuprimable()
        ));
        }
        if ($personne->getLdap()){
            return $this->redirect($this->generateUrl('PASS_droitGroupeUtilisateur', array('personne' => $personne->getId())));
        }
         return $this->render('PASSAuthentificationLogBundle:authentification:editPersonneForm.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Modifier un utilisateur local',
                    'id' => $personne->getId(),
                    'suprimable' => $personne->getSuprimable(),
                 'utilisateur' => $personne->getUsername()
                 ));
        
    }
    /**
     * 
     * @Secure(roles="ROLE_DROIT_U, ROLE_ADMIN")
     */
    public function roleUserAction(Request $request,Personne $personne){
         $form = $this->createForm(new groupeRoleType($this->getDoctrine() ->getRepository('PASSAuthentificationLogBundle:Groupe'), $this->getDoctrine()->getRepository('PASSAuthentificationLogBundle:Role')), $personne);
    
         
         $groupetmp = clone  $personne->getGroupes();
         $roletmp = clone  $personne->getRoles();
         
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
          
            
            //$user->setMdp($encoder->encodePassword($personne->getMdp(), $personne->getSalt()));
            foreach($groupetmp as $groupe){
                if(! $groupe->getSupprimable()) $personne->addGroupe($groupe);
            }
            foreach($roletmp as $role){
                if($role->getType() =="") $personne->addRole($role);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($personne);
           
            $em->flush();
             $token = new UsernamePasswordToken(
           $this->getUser(),
            null,
            'ldap',
            $this->getUser()->getAllRoles()
                  //array("ROLES_ADMIN")
            );
            $this->container->get('security.context')->setToken($token);    
           
           
             $utilisateur = $this->getDoctrine()->getRepository("PASSAuthentificationLogBundle:Personne")->find($personne->getId());

            return $this->render('PASSAuthentificationLogBundle:listing:recapitulatif.html.twig', Array(
                        'titrePage' => 'Gestion d\'un utilisateur', 'groupe' => $utilisateur->resumer(), 'nom' => $utilisateur->getUsername(),
                        'activiter' => " Information sur l'utilisateur", 'lien' => 'PASS_ModificationUtilisateur', 'id' => $personne->getId(),
                        'activite' => "utilisateur", "good" => "Modification éffectué"
            ));
        }
         return $this->render('PASSAuthentificationLogBundle:authentification:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Modifier les groupes et les roles d\'un utilisateur',
                    'id' => $personne->getId(),
                    'suprimable' => $personne->getSuprimable()
        ));
        
         
         
         
    }
    
     public function roleGroupeAction(Request $request,Groupe $groupe){
         $form = $this->createForm(new roleType( $this->getDoctrine()->getRepository('PASSAuthentificationLogBundle:Role')), $groupe);
    
         
         
         $roletmp = clone  $groupe->getRoles();
         
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
          
            
            //$user->setMdp($encoder->encodePassword($personne->getMdp(), $personne->getSalt()));
            
            foreach($roletmp as $role){
                if($role->getType() =="") $groupe->addRole($role);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
           
            $em->flush();
             $token = new UsernamePasswordToken(
           $this->getUser(),
            null,
            'ldap',
            $this->getUser()->getAllRoles()
                  //array("ROLES_ADMIN")
            );
            $this->container->get('security.context')->setToken($token);    
           
           
             $utilisateur = $this->getDoctrine()->getRepository("PASSAuthentificationLogBundle:groupe")->find($groupe->getId());

            return $this->render('PASSAuthentificationLogBundle:listing:recapitulatif.html.twig', Array(
                        'titrePage' => 'Gestion d\'un groupe', 'groupe' => $groupe->resumer(), 'nom' => $groupe->getNom(),
                        'activiter' => " Information sur le groupe", 'lien' => 'PASS_ModificationGroupe', 'id' => $groupe->getId(),
                        'activite' => "groupe" ));
        }
         return $this->render('PASSAuthentificationLogBundle:authentification:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Modifier les groupes et les roles d\'un utilisateur',
                    'id' => $groupe->getId(),
                    
        ));
        
         
         
         
    }
}
