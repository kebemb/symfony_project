<?php

namespace App\Form;

use App\Entity\FoodTruck;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('codePostal')
            ->add('superficie')
            ->add('foodTrucks', EntityType::class,[
                'class' =>FoodTruck::class ,
                'choice_label' =>'nom',
                'multiple' =>true,
                'expanded' =>true,
                'by_reference' =>false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
