<?php

namespace PASS\AuthentificationLogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class editPersonneNonSupType extends AbstractType
{
    
     private $em;
    private $emrole;
    private $gest;
    public function __construct($em,$em2,$gest) {
        $this->em = $em;
        $this->emrole = $em2;
        $this->gest = $gest;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('username','text',array("label" =>"Nom d'utilisateur:","disabled"=>'false'))
                
            
             //-> add('mot de pass','')
            //->add('dernierConnexion')
           // ->add('ldap')
            //->add('actif',null,array("label"=>"Compte utilisateur actif?", "action"=>'checked'))
            ->add('mail');
         if ($this->gest->isGranted('ROLE_MEMORY') ) 
            $builder->add('actif',null,array("label"=>"Compte utilisateur actif?", "action"=>'checked'));
              if ($this->gest->isGranted('ROLE_USER_U') || $this->gest->isGranted('ROLE_ADMIN'))  
           $builder->add('groupes',null,array("label"=> "Groupe (contrôle  + click souris pour multi-sélection):",  "choices" => $this->em->getGroupeVisible() ));
            if ($this->gest->isGranted('ROLE_DROIT_U') || $this->gest->isGranted('ROLE_ADMIN')) 
              $builder->add('roles',null,array("label"=> "Rôle (contrôle  + click souris pour multi-sélection):", "choices" =>$this->emrole->getType()) );
              //->add('ldap')
            if ($this->gest->isGranted('ROLE_USER_U') || $this->gest->isGranted('ROLE_ADMIN')) 
            $builder->add('actif');
              $builder->add('Enregistrer','submit')
              
              
        ;
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PASS\AuthentificationLogBundle\Entity\Personne',
             
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pass_authentificationlogbundle_personne';
    }
}
