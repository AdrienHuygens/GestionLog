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

class ConfigurationServeurType extends AbstractType
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
            
            ->add('srv_date_log','choice',array("label"=>"durée de vie d'un log","choices"=>array('1 days'=>'1 jour','7 days' =>'7 jours', '1 months' =>'1 mois'
                                ,'2 months' =>'2 mois','3 months' =>'3 mois','6 months' =>'6 mois','9 months' =>'9 mois', '1 years' =>'1 an','2 years' =>'2 ans','3 years' =>' 3 ans','5 years' =>'5 ans','avie' =>'pas de limite'
                                                        ),'required' => true))
            
                   ;
         if ($this->val->isGranted('ROLE_CONFIGURATION_U') || $this->val->isGranted('ROLE_ADMIN') )
             $builder-> add("Enregistrer",'submit')
                        -> add("Vider",'submit',array('label'=>"Vider la bdd"))
                        
        ;
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PASS\GeneralLogBundle\Entity\ConfigurationServeur',
              
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
