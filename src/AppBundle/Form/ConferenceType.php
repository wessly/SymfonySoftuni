<?php

namespace AppBundle\Form;

use AppBundle\Entity\Conference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('venue', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('halls', TextType::class)
            ->add('description', TextareaType::class)
            ->add('appointed', DateType::class)
            ->add('status', ChoiceType::class, array(
                'choices' => array('Closed conference' => '0', 'Open conference' => '1'),
            ));

        $builder->add('lectures', CollectionType::class, array(
            'entry_type' => LectureType::class,
            'allow_add'    => true,
            'allow_delete' => true,
            'prototype'    => true,
        ));

        $builder->add('speakers', CollectionType::class, array(
            'entry_type' => SpeakerType::class,
            'allow_add'    => true,
            'allow_delete' => true,
            'prototype'    => true,
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Conference::class,
            ]
        );
    }

    public function getName()
    {
        return 'app_bundle_conference_type';
    }
}
