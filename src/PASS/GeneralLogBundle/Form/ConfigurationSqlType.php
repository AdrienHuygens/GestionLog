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

class ConfigurationSqlType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
     private $val;
    public function __construct($val) {
        $this->val = $val;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('database_driver','choice',array("choices"=>array('pdo_pgsql'=>'pdo_pgsql','pdo_mysql' =>'pdo_mysql'),'required' => true))
            ->add('database_host', 'text')
            ->add('database_port', 'text',array('label'=>"test",'required' => false))
            ->add('database_name', 'text')
            ->add('database_user', 'text')
            ->add('databasePassword', 'text')
                   ;
         if ($this->val->isGranted('ROLE_CONFIGURATION_U') || $this->val->isGranted('ROLE_ADMIN') )
             $builder-> add("Enregistrer",'submit')
            
        ;
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PASS\GeneralLogBundle\Entity\ConfigurationSql',
              
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pass_generallogbundle_configurationSql';
    }
}
