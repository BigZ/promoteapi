<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Gig;
use App\Entity\Label;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['required' => true])
            ->add('slug', TextType::class, ['required' => true])
            ->add('bio', TextareaType::class)
            ->add('gigs', EntityType::class, ['class' => Gig::class, 'multiple' => true])
            ->add('labels', EntityType::class, ['class' => Label::class, 'multiple' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
            'csrf_protection' => false,
        ]);
    }
}
