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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('ldap_server', 'text')
            ->add('ldap_port', 'integer',array('label'=>"test",'required' => false))
            ->add('ldap_dn', 'text')
            ->add('ldap_filtre', 'text')
            ->add('ldap_connexion', 'checkbox',array('label'=>"Connexion par ldap",'required' => false))
             -> add("Enregistrer",'submit')
             
            
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
