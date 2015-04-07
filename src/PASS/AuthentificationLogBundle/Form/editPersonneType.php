<?php

namespace PASS\AuthentificationLogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class editPersonneType extends AbstractType
{
    
     private $em;
    private $emrole;
    public function __construct($em,$em2) {
        $this->em = $em;
        $this->emrole = $em2;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username','text',array("label" =>"Nom d'utilisateur:"))
            //->add('mdp','password',array("label"=> "Mot de pass pour l'utilisateur:"))
             //-> add('mot de pass','')
            //->add('dernierConnexion')
           // ->add('ldap')
            //->add('actif',null,array("label"=>"Compte utilisateur actif?", "action"=>'checked'))
            ->add('mail')
            ->add('groupes',null,array("label"=> "Groupe (control + click souri pour multi-selection):",  "choices" => $this->em->getGroupeVisible() ))
            ->add('roles',null,array("label"=> "Role (control + click souri pour multi-selection):", "choices" =>$this->emrole->getType()) )
              //->add('ldap')
            ->add('actif')
             ->add('Enregistrer','submit')
              
              
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
