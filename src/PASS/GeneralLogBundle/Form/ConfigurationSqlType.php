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
     private $color;
    public function __construct($val) {
        $this->val = $val;
    }
      public function setColor($color){
        $this->color = $color;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('database_driver','choice',array("choices"=>array('pdo_pgsql'=>'pdo_pgsql','pdo_mysql' =>'pdo_mysql'),'required' => true))
            ->add('database_host', 'text')
            ->add('database_port', 'text',array('label'=>"port",'required' => false))
            ->add('database_name', 'text')
            ->add('database_user', 'text')
            ->add('databasePassword', 'password')
                   ;
         if ($this->val->isGranted('ROLE_CONFIGURATION_U') || $this->val->isGranted('ROLE_ADMIN') )
             $builder-> add("Enregistrer",'submit')
                         -> add("Tester",'submit', array('label'=>'tester la connexion','attr' => array('class' => $this->color)))
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
