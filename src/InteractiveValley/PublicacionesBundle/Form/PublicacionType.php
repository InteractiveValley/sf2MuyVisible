<?php

namespace InteractiveValley\PublicacionesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use InteractiveValley\BackendBundle\Form\DataTransformer\UsuarioToNumberTransformer;

class PublicacionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $options['em'];
        $usuarioTransformer = new UsuarioToNumberTransformer($em);
        
        $builder
            ->add('title','text',array('label'=>'Titulo','attr'=>array('class'=>'form-control')))
            ->add('slug','hidden')
            ->add('resume',null,array(
                'label'=>'Resumen',
                'required'=>true,
                'attr'=>array(
                    'class'=>'resume form-control placeholder',
                   'data-theme' => 'advanced',
                    )
                ))
            ->add('description',null,array(
                'label'=>'Descripcion',
                'required'=>true,
                'attr'=>array(
                    'class'=>'description form-control placeholder',
                   'data-theme' => 'advanced',
                    )
                ))
            ->add('file','file',array('label'=>'Portada','attr'=>array('class'=>'form-control')))
            ->add('contComments','hidden')
            ->add('contLikes','hidden')
            ->add('contTwits','hidden')
            ->add('contViews','hidden')
            ->add('position','hidden')
            ->add('isActive',null,array('label'=>'Activo?','attr'=>array(
                'class'=>'checkbox-inline',
                'placeholder'=>'Es activo',
                'data-bind'=>'value: isActive'
                )))
            ->add('categorie','entity',array(
                'class'=> 'PublicacionesBundle:Categoria',
                'label'=>'Categoria',
                'required'=>true,
                'read_only'=>false,
                'choice_label'=>'name',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'attr'=>array(
                    'class'=>'form-control placeholder',
                    'placeholder'=>'Categoria',
                    'data-bind'=>'value: categoria',
                    )
                ))
            ->add($builder->create('author', 'hidden')->addModelTransformer($usuarioTransformer))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PublicacionesBundle\Entity\Publicacion'
        ))
        ->setRequired(array('em'))
        ->setAllowedTypes(array('em' => 'Doctrine\Common\Persistence\ObjectManager'))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_publicacionesbundle_publicacion';
    }
}
