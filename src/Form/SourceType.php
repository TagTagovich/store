<?php

namespace App\Form;

use App\Entity\Source;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', VichFileType::class, [
            'label' => '',
            'required' => false,
            'allow_delete' => true,
            'download_label' => 'Скачать файл',
            'allow_delete' => false,
            'delete_label' => 'Удалить файл',
            'asset_helper' => false,
            ])
            ->add('width', TextType::class, ['label' => 'Ширина'])
            ->add('height', TextType::class, ['label' => 'Высота']);
            //->add('dpi', TextType::class, ['label' => 'DPI']);
            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Source::class,
        ]);
    }
}
