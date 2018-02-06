<?php

namespace AppBundle\Form\Type;

// -----------------------------------------------------------------------------

use Doctrine\ORM\EntityRepository;

// -----------------------------------------------------------------------------

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

// -----------------------------------------------------------------------------

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

// -----------------------------------------------------------------------------

class ShowType extends AbstractType
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
        $builder->add
        (
            'category',
            EntityType::class,
            array
            (
                'class' => 'AppBundle:Category',
                'query_builder' => function(EntityRepository $er)
                {
                    return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Choisissez la catégorie',
            )
        );
        $builder->add
        (
            'abstract',
            TextareaType::class,
            array
            ( )
        );
        $builder->add
        (
            'country',
            CountryType::class,
            array
            (
                'preferred_choices' => array('FR')
            )
        );
        $builder->add
        (
            'author',
            TextType::class,
            array
            ( )
        );
        $builder->add
        (
            'releaseDate',
            DateType::class,
            array
            ( )
        );
        $builder->add
        (
            'mainPicture',
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
            ( )
        );
    }

    
}