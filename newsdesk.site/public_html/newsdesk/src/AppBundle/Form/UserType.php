<?php

namespace AppBundle\Form;

use Doctrine\DBAL\Types\IntegerType;
//use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('age')
            ->add('gender', ChoiceType::class, array(
                'choices'  => array(
                    'male' => true,
                    'female' => false,
                )))
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'first_options' =>
                        [
                            'label' => 'Password'
                        ],
                    'second_options' =>
                        [
                            'label' => 'Repeat Password'
                        ]
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
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
