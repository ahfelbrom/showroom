<?php

namespace AppBundle\Form\Type;

// -----------------------------------------------------------------------------

use Doctrine\ORM\EntityRepository;

// -----------------------------------------------------------------------------

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

// -----------------------------------------------------------------------------

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

// -----------------------------------------------------------------------------

class UserType extends AbstractType
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
            'fullname',
            TextType::class,
            array
            ( )
        );
        $builder->add
        (
            'roles',
            TextType::class,
            array(
                'label' => 'Roles separated by commas (, )'
            )
        );
        $builder->get('roles')->addModelTransformer(new CallbackTransformer(
            function($rolesAsArray) {
                // From Model to view => Array to string
                if (!empty($rolesAsArray))
                {
                    return implode(", ", $rolesAsArray);
                }
            },
            function($rolesAsString) {
                // From view to Model => string to Array
                return explode(", ", $rolesAsString);
            }
        ));
        $builder->add
        (
            'username',
            EmailType::class,
            array
            ( )
        );
        $builder->add
        (
            'password',
            RepeatedType::class,
            array
            (
                'type' => PasswordType::class,
                'first_options' => array(
                    'label' => 'Password'
                ),
                'second_options' => array(
                    'label' => 'Repeat password'
                ),
                'invalid_message' => 'the passwords must match'
            )
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