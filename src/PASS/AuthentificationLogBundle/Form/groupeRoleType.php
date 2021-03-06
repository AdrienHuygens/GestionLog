<?php

namespace PASS\AuthentificationLogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class groupeRoleType extends AbstractType
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
            
            ->add('username','text',array("label" =>"Nom d'utilisateur:","disabled"=>'false'))
            ->add('groupes',null,array("label"=> "Groupe (contrôle + click souris pour multi-sélection):",  "choices" => $this->em->getGroupeVisible() ))
            ->add('roles',null,array("label"=> "Rôle (contrôle + click souris pour multi-sélection):", "choices" =>$this->emrole->getType()) )
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
