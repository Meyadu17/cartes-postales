<?php

namespace App\Form;

use App\Entity\CartePostale;
use App\Entity\Departement;
use App\Entity\Ville;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartePostaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr'  => ['placeholder' => 'Titre de la carte postale'],
            ])
            ->add('description', TextareaType::class, [
                'label'    => 'Description',
                'required' => false,
                'attr'     => ['rows' => 4],
            ])
            ->add('annee', IntegerType::class, [
                'label'    => 'Année',
                'required' => false,
                'attr'     => ['placeholder' => 'ex: 1920'],
            ])
            ->add('orientation', ChoiceType::class, [
                'label'   => 'Orientation',
                'choices' => [
                    'Paysage'  => 'paysage',
                    'Portrait' => 'portrait',
                ],
                'placeholder' => 'Choisir une orientation',
            ])
            ->add('enCaroussel', CheckboxType::class, [
                'label'    => 'Afficher dans le carrousel',
                'required' => false,
            ])
           ->add('departement', EntityType::class, [
                'class'         => Departement::class,
                'label'         => 'Département',
                'placeholder'   => 'Choisir un département',
                'choice_label'  => fn (Departement $d) => $d->getCode() . ' - ' . $d->getNom(),
                'query_builder' => fn (DepartementRepository $r) => $r->createOrderedByCodeQueryBuilder(),
            ])

            // Champs image — pas mappés directement sur l'entité
            ->add('logoNom', TextType::class, [
                'label'    => 'Nom du fichier image',
                'required' => false,
                'mapped'   => false,
                'attr'     => ['placeholder' => 'ex: image-nord'],
            ])
            ->add('logoBase64', HiddenType::class, [
                'required' => false,
                'mapped'   => false,
            ])
        ;

        // Ville : ajoutée dynamiquement selon le département sélectionné
        $formModifier = function (FormInterface $form, ?Departement $departement = null) {
            $form->add('ville', EntityType::class, [
                'class'         => Ville::class,
                'choice_label'  => 'nom',
                'label'         => 'Ville',
                'placeholder'   => $departement ? 'Choisir une ville' : 'Sélectionnez d\'abord un département',
                'required'      => false,
                'query_builder' => function (EntityRepository $er) use ($departement) {
                    $qb = $er->createQueryBuilder('v')->orderBy('v.nom', 'ASC');
                    if ($departement) {
                        $qb->andWhere('v.departement = :dept')
                           ->setParameter('dept', $departement);
                    } else {
                        // Aucune ville tant qu'aucun département n'est choisi
                        $qb->andWhere('1 = 0');
                    }
                    return $qb;
                },
            ]);
        };

        // Pré-remplissage à l'affichage (édition)
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                /** @var CartePostale|null $data */
                $data = $event->getData();
                $formModifier($event->getForm(), $data?->getDepartement());
            }
        );

        // Quand le département change côté client (re-soumission)
        $builder->get('departement')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $departement = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $departement);
            }
        );

        // Affecter automatiquement la région à partir du département choisi
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                /** @var CartePostale $carte */
                $carte = $event->getData();
                if ($carte && $carte->getDepartement()) {
                    $carte->setRegion($carte->getDepartement()->getRegion());
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'      => CartePostale::class,
            'csrf_protection' => false,
        ]);
    }
}
