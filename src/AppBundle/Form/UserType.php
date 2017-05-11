<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, [
            'required' =>  false,
            'attr' => array('class' => 'mdl-textfield__input'),
        ])
            ->add('email', TextType::class,[
                'attr' => array('class' => 'mdl-textfield__input'),
                'required' =>  false,
            ])
            ->add('password_raw', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => array('class' => 'mdl-textfield__input'),
                    'required' =>  false,
                    'label'=> 'Password',

                ],
                'second_options' =>[
                    'attr' => array('class' => 'mdl-textfield__input'),
                    'required' =>  false,
                    'label'=> 'Repeat Password'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => array('class' => 'mdl-button mdl-js-button mdl-button--raised mdl-button--accent margintop25'),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>  User::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }

}
