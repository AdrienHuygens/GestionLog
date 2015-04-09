<?php

namespace PASS\AuthentificationLogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class roleType extends AbstractType
{
    
    
    private $emrole;
    public function __construct($em2) {
      
        $this->emrole = $em2;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('nom','text',array("label" =>"Nom du groupe:","disabled"=>'false'))
            ->add('roles',null,array("label"=> "Role (control + click souri pour multi-selection):", "choices" =>$this->emrole->getType()) )
              //->add('ldap')
            //->add('actif')
             ->add('Enregistrer','submit')
              
              
        ;
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PASS\AuthentificationLogBundle\Entity\Groupe',
             
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pass_authentificationlogbundle_groupe';
    }
}
