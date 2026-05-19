<?php

namespace App\Form;

use App\Entity\CartePostale;
use App\Entity\Departement;
use App\Entity\Ville;
use App\Repository\DepartementRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostcardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank(message: 'Le titre est obligatoire'),
                    new Length(max: 255),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('annee', IntegerType::class, [
                'label' => 'Année',
                'required' => false,
            ])
            ->add('orientation', ChoiceType::class, [
                'label' => 'Orientation',
                'choices' => [
                    'Paysage' => 'paysage',
                    'Portrait' => 'portrait',
                ],
                'constraints' => [
                    new NotBlank(message: 'L\'orientation est obligatoire'),
                ],
            ])
            ->add('enCaroussel', CheckboxType::class, [
                'label' => 'Afficher dans le carrousel',
                'required' => false,
            ])
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => fn(Departement $d) => $d->getCode() . ' - ' . $d->getNom(),
                'label' => 'Département',
                'query_builder' => fn(DepartementRepository $repo) => $repo->createOrderedByCodeQueryBuilder(),
                'placeholder' => '-- Sélectionner un département --',
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'label' => 'Ville',
                'required' => false,
                'placeholder' => '-- Sélectionner une ville --',
            ])
            // TODO logoNom quand on s'occupera de l'image.
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CartePostale::class,
        ]);
    }
}

