<?php

namespace App\Form;

use App\Entity\Departement;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
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
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => function (Departement $departement) {
                    return $departement->getNom() . ' - ' . $departement->getCode();
                },
                'placeholder' => 'Choisir un département',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nom', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
            'csrf_protection' => false,
        ]);
    }
}
