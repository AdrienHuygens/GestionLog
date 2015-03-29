<?php

namespace PASS\GestionLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PASS\GestionLogBundle\Entity\GroupeOrdinateur;
use PASS\GestionLogBundle\Form\GroupeOrdinateurType;

use Symfony\Component\HttpFoundation\Request;

class GroupeOrdinateurController extends Controller
{
    public function indexAction(){
        
         return $this->render('PASSGeneralLogBundle:form:ok.html.twig', Array(
                        "good" => "utilisateur d'utilisateur bien créé.",
                        'titrePage' => 'Opération éffectué',
            ));
        
    }
    private function host(){
         $repo = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\Systemevents");
         $host = array();
         $temp = $repo->getHost();
         
         foreach($temp as $hote){
             $host[$hote['fromhost']]= $hote['fromhost'];
         }
         return $host;
    }
    
    public function groupeOrdinateurAddAction(Request $request){
        
    
       $chemin ="";

        $Groupe = new GroupeOrdinateur();
        
        
        


        $form = $this->createForm(new GroupeOrdinateurType($this->host()), $Groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($Groupe);
            $em->flush();

            return $this->render('PASSGeneralLogBundle:form:ok.html.twig', Array(
                        "good" => "utilisateur d'utilisateur bien créé.",
                        'titrePage' => 'Opération éffectué',
            ));
        }
        return $this->render('PASSGeneralLogBundle:form:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Rajouter un utilisateur local',
                    'chemin' => $chemin
        ));
    }
        
  public function GroupeOrdinateurModificationAction(Request $request, GroupeOrdinateur $listingId) {
       $chemin ="";
        $Groupe = $listingId;
        $form = $this->createForm(new GroupeOrdinateurType($this->host()), $Groupe);
        // $request = $this->getRequest();
        //$form->bindRequest($request);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            $em = $this->getDoctrine()->getManager();
            $em->persist($Groupe);
            $em->flush();
            //return $this->redirect($this->generateUrl('PASS_GestionUtilisateur', array('listingId' => $listingId->getId())));
        }

        return $this->render('PASSGeneralLogBundle:form:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Modifier un utilisateur local',
                    
                    'chemin' => $chemin));
    }

        
     public function groupeOrdinateurListingAction($listingId) {
        if ($listingId != 0) {
            
            $groupe = $this->getDoctrine()->getRepository("PASSGestionLogBundle:GroupeOrdinateur")->find($listingId);

            return $this->render('PASSGeneralLogBundle:form:recapitulatif.html.twig', Array(
                        'titrePage' => 'Gestion d\'un Groupe d\'ordinateur', 'groupe' => $groupe->resumer(), 'nom' => $groupe->getNom(),
                        'activiter' => " Information sur l'utilisateur", 'lien' => 'PASS_GroupeOrdinateurModification', 'id' => $listingId
            ));
        } else {
            $repoJeune = $this->getDoctrine()->getRepository("PASSGestionLogBundle:GroupeOrdinateur");
            $tab = $repoJeune->getNameGroupe();

            return $this->render("PASSGeneralLogBundle:form:listing.html.twig", array("titrePage" => "Listing des groupes D'ordinateur", "activite" => 'groupe',
                        "tab" => $tab, 'chemin' => "PASS_GroupeOrdinateurListing"
            ));
        }
    }
    
}
