<?php

namespace App\Form;

use App\Entity\Region;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,  // ← doit être true
                'attr' => ['placeholder' => 'Nom'],
            ])
            ->add('code', TextType::class, [
                'label' => 'Code',
                'required' => true,  // ← doit être true
                'attr' => ['placeholder' => 'Code'],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Region::class,
        ]);
    }
}
