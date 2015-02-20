<?php

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

class AuthentificationController extends Controller {

    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction() {
        return $this->render('PASSAuthentificationLogBundle:authentification:index.html.twig', array(
                    'titrePage' => "test"
        ));
    }

    /*
     *  Controleur pour le formulaire de mon rajout de groupe.
     */

    public function groupeAddAction(Request $request) {
        $groupe = new Groupe();
        $groupe->setLdap(False);
        $groupe->setActif(True);
        $groupe->setRole('ROLE_ADMIN');

        $form = $this->createForm(new GroupeType(), $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();
            //$this->addFlash('success', '')
            ///$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            //return $this->redirect($this->generateUrl('', array('id' => $advert->getId())));
            return $this->render('PASSAuthentificationLogBundle:authentification:ok.html.twig', Array(
                        "good" => "Groupe bien créé.",
                        'titrePage' => 'Opération éffectué'
            ));
        }

        return $this->render('PASSAuthentificationLogBundle:authentification:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Rajouter un groupe local'));
    }

    public function utilisateurAddAction(Request $request, $listingId) {



        $user = new Personne();
        $user->setActif(True);
        $user->setLdap(False);

        $form = $this->createForm(new PersonneType(), $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);

            $user->setMdp($encoder->encodePassword($user->getMdp(), $user->getSalt()));


            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->render('PASSAuthentificationLogBundle:authentification:ok.html.twig', Array(
                        "good" => "Groupe d'utilisateur bien créé.",
                        'titrePage' => 'Opération éffectué'
            ));
        }
        return $this->render('PASSAuthentificationLogBundle:authentification:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Rajouter un utilisateur local'));
    }

    /**
     * fonction pour ajouter un role a la bdd et création du formulaire de celui si.
     * 
     * @param Request $request récuperation des champs
     */
    public function roleAddAction(Request $request) {
        
    }

    /**
     * function pour la vérification de connexion.
     */
    public function loginAction() {
        $request = $this->getRequest();
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

    public function okAction() {
        $em = $this->getDoctrine()->getManager();

        $this->getUser()->setDernierConnexion(new \DateTime());
        $em->persist($this->getUser());
        $em->flush();
        return $this->render("PASSAuthentificationLogBundle:authentification:ok.html.twig", Array(
                    'titrePage' => 'Connexion effectué',
                    'good' => null));
    }

    public function utilisateurListingAction($listingId) {
       if ($listingId != 0){
          
           $groupe = $this->getDoctrine()->getRepository("PASSAuthentificationLogBundle:Personne")->find($listingId);
          
            return $this->render('PASSAuthentificationLogBundle:listing:recapitulatif.html.twig', Array(
                        'titrePage' => 'Gestion d\'un utilisateur', 'groupe' => $groupe->resumer(),'nom' => $groupe->getUsername(),
                           'activiter' => " Information sur l'utilisateur"
                ));
            }
       else{
           $repoJeune = $this->getDoctrine()->getRepository("PASS\AuthentificationLogBundle\Entity\Personne");
            $tab = $repoJeune->getAllUser();
           
            return $this->render("PASSAuthentificationLogBundle:listing:listing.html.twig", array("titrePage" => "Listing utilisateur","activite" => 'utilisateur', "tab" => $tab,'chemin' => "PASS_GestionUtilisateur"));
       }
       
       
    }
    
     public function groupeListingAction($listingId) {
        if ($listingId != 0){
            
            $groupe = $this->getDoctrine()->getRepository("PASSAuthentificationLogBundle:Groupe")->find($listingId);
          
            return $this->render('PASSAuthentificationLogBundle:listing:recapitulatif.html.twig', Array(
                        'titrePage' => 'Gestion d\'un groupe', 'groupe' => $groupe->resumer(),'nom' => $groupe->getNom(),
                           'activiter' => " Information sur le groupe"
            ));
            
     }
       else{
          
            $repoJeune = $this->getDoctrine()->getRepository("PASS\AuthentificationLogBundle\Entity\Groupe");
            $tab = $repoJeune->getAllGroupe();
           
            return $this->render("PASSAuthentificationLogBundle:listing:listing.html.twig", array("titrePage" => "Listing des groupes","activite" => 'groupe', "tab" => $tab, 'chemin' => "PASS_GestionGroupe",));
          
       }

   
     }
}
