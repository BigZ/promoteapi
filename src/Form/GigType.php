<?php

namespace App\Form;

use App\Entity\Gig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('startDate', DateTimeType::class, ['widget' => 'single_text'])
            ->add('endDate', DateTimeType::class, ['widget' => 'single_text'])
            ->add('venue', TextareaType::class)
            ->add('address', TextareaType::class)
            ->add('facebookLink', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gig::class,
            'csrf_protection' => false,
        ]);
    }
}
