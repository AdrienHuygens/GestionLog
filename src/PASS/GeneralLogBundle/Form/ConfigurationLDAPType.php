<?php

/* 


   * Copyright 2015 Version 1.0.0
   * Pour le Pass, projet gestion de log.
   * @author Huygens Adrien
   * contact adrien.huygens@gmail.com
 
 */

namespace PASS\GeneralLogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigurationLDAPType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
     private $val;
     private $color="";
    public function __construct($val) {
        $this->val = $val;
    }
    
    public function setColor($color){
        $this->color = $color;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('ldap_server', 'text')
            ->add('ldap_port', 'integer',array('label'=>"test",'required' => false))
            ->add('ldap_dn', 'text')
            ->add('ldap_filtre', 'text')
            ->add('ldap_connexion', 'checkbox',array('label'=>"Connexion par ldap",'required' => false))
                ;
         if ($this->val->isGranted('ROLE_CONFIGURATION_U') || $this->val->isGranted('ROLE_ADMIN') )
             $builder-> add("Enregistrer",'submit')
                      -> add("Tester",'submit', array('label'=>'Tester la conexion','attr' => array('class' => $this->color)))
             
            
        ;
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PASS\GeneralLogBundle\Entity\ConfigurationLDAP',
              
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pass_generallogbundle_configurationLDAP';
    }
}
