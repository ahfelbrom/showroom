<?php

namespace AppBundle\Type;

use AppBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ShowType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
    		->add('name', TextType::class, ['required'=> false])
    		->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
    		->add('abstract')
    		->add('country', CountryType::class, ['preferred_choices' => array('FR', 'US', 'CA')])
    		->add('releaseDate')
    		->add('tmpPicture', FileType::class, ['label' => 'Main Picture'])
    	;
    }
}