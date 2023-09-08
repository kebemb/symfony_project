<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('description')
            ->add('photoPrincipal', FileType::class, [
                'label' => 'Brochure (PDF file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide',
                    ])
                ],
            ])
            ->add('dateDispo', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('avecJardin')
            ->add('ville', EntityType::class,[
                'class' =>Ville::class,
                'choice_label' =>'nom'
            ])

            ->add('bienUsers', CollectionType::class,[
                'entry_type' => BienUserType::class,
                'allow_add' =>true,
                'allow_delete' => true,
                'by_reference' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
        ]);
    }
}
