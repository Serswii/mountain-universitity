<?php

namespace App\Form;

use App\Entity\Gallery;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class GalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_photo', FileType::class, [
                'mapped' => false,
                'label' => 'Фотография',
                'required' => true,
                'attr' => [
                    'class' => 'form-control pb-2',
                ],
                'label_attr' => [
                    'class' => 'pb-2',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/pjpeg',
                        ],
                        'mimeTypesMessage' => 'Пожалуйста загрузите фотографию формата png, jpeg',
                        'maxSizeMessage' => 'Файл превышает допустимый размер, пожалуйста загрузите другой. Допустимый максимальный размер составляет 1024 kB'
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gallery::class,
        ]);
    }
}
