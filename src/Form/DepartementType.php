<?php

namespace App\Form;

use App\Entity\Departement;
use App\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('code')
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une région',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Departement::class,
            'csrf_protection' => false,
        ]);
    }
}
