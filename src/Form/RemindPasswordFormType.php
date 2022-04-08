<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemindPasswordFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Введите свой email']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Напомнить пароль',
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'app_remind_password';
    }
}