<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBl;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['class' => 'form-control'], 'label' => 'Электронная почта'])
            /*->add('roles', ChoiceType::class, [
            	'choices' => [
		            'Пользователь' => 'ROLE_USER'
	            ],
		        'attr' => ['class' => 'form-control'],
                'label' => 'Роль',
                'multiple' => true,
		        'expanded' => true
	        ])*/
            ->add('plainPassword', PasswordType::class, ['attr' => ['class' => 'form-control'], 'label' => 'Пароль'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
