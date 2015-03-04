<?php

namespace PASS\GestionLogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('signe','choice',array("choices"=>array('='=>'=','<'=>'<','between' =>'entre'),'required' => false))
            ->add('date1','datetime', array('required' => false, ))
             ->add('date2','datetime', array('required' => false, ))
            
        ;
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PASS\GestionLogBundle\Entity\Date',
              
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pass_gestionlogbundle_date';
    }
}
