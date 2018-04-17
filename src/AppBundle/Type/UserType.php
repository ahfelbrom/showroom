<?php

namespace AppBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('fullname')
			->add('username')
			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'first_options'  => ['label' => 'Password'],
    			'second_options' => ['label' => 'Repeat Password'],
    			'invalid_message' => 'The password fields must match.',
			])
			->add('roles', TextType::class, ['label' => 'Roles (separated by commas (, )'])
			->add('Save', SubmitType::class)
		;

		$builder->get('roles')
		    ->addModelTransformer(new CallbackTransformer(
		        function ($rolesAsArray) {
		        	// From Model to View ➡️ Array to String
					if (!empty($rolesAsArray)) {
						
		        		return implode(', ', $rolesAsArray);
					}
		        },
		        function ($rolesAsString) {
		        	// From View to Model ➡️ String to Array
		        	return explode(', ', $rolesAsString);
		        }
		    ))
		;
	}
}