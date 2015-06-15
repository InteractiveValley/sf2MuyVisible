<?php

namespace InteractiveValley\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use InteractiveValley\BackendBundle\Entity\Configuraciones;

class ConfiguracionesTextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('configuracion','text',array(
                'label'=>'Configuracion',
                'required'=>true,
                'attr'=>array(
                    'class'=>'validate[required] form-control placeholder',
                    'placeholder'=>'Configuracion',
                    'data-bind'=>'value: configuracion'
             )))
            ->add('texto','textarea',array(
                'label'=>'Texto largo',
                'required'=>true,
                'attr'=>array(
                    'class'=>'description form-control placeholder'
                    )
                ))
            ->add('slug','hidden')
            ->add('tipoConfiguracion','hidden') 
            
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\BackendBundle\Entity\Configuraciones'
        ));
    }

    public function getName()
    {
        return 'richpolis_backendbundle_configuracionestype';
    }
}
