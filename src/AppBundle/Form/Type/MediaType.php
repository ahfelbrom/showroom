<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MediaType extends AbstractType
{
    /**
     * the build of the rendered form to create a project
     *
     * @param FormBuilderInterface $builder
     *
     * @param array $options
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add
        (
            'file',
            FileType::class,
            array
            ( )
        );
    }
    
    /**
     * function used to set default options to the form
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults
        (
            array
            (
                'data_class' => 'AppBundle\Entity\Media',
            )
        );
    }
}