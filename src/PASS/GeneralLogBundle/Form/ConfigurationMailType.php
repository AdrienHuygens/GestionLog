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

class ConfigurationMailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('titre', 'text')
            ->add('body', 'textarea',array('label'=>"Corp du massage",'required' => false, 'attr' => array('style' => 'height:300px;')))
            ->add('css', 'textarea',array('label'=> "Codage strict:", 'required'=> false,'attr' => array('style' => 'height:200px;')))
             ->add('strict', 'checkbox',array( 'required'=> false,  'attr' => array('checked'   => 'checked')))
           
             -> add("Enregistrer",'submit')
             -> add("Prévisualiser",'submit')
             
           
        ;
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PASS\GeneralLogBundle\Entity\ConfigurationMail',
              
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pass_generallogbundle_configurationMail';
    }
}
