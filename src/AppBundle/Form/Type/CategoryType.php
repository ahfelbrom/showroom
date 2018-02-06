<?php

namespace AppBundle\Form\Type;

// -----------------------------------------------------------------------------

use Doctrine\ORM\EntityRepository;

// -----------------------------------------------------------------------------

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// -----------------------------------------------------------------------------

use Symfony\Component\Form\Extension\Core\Type\TextType;

// -----------------------------------------------------------------------------

class CategoryType extends AbstractType
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
            'name',
            TextType::class,
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
            ( )
        );
    }

    
}