<?php

namespace PASS\AuthentificationLogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    
    
    private $emrole;
    public function __construct($em2) {
   
        $this->emrole = $em2;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom','text', array('label' => 'Nom du groupe:'))
            ->add('description', null, array('label' => 'Description du groupe:'))
             ->add('roles',null,array("label"=> "Role (contrôle  + click souris pour multi-sélection):", "choices" =>$this->emrole->getType()) )
             
            //->add('ldap')
            //->add('actif')
            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PASS\AuthentificationLogBundle\Entity\Groupe'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pass_authentificationlogbundle_groupe';
    }
    public function __clone(){
        
    }
}
