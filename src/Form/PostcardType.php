<?php

namespace App\Form;

use App\Entity\CartePostale;
use App\Entity\Departement;
use App\Entity\Ville;
use App\Repository\DepartementRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostcardType extends AbstractType
{
    public function __construct(private VilleRepository $villeRepo,private EntityManagerInterface $em) {}

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
            //TODO : Ajouter logoImage
        ;

        // Fonction qui (re)construit le champ ville selon le département
        $formModifier = function (FormInterface $form, ?Departement $departement) {
            $villes = $departement
                ? $this->villeRepo->findBy(['departement' => $departement], ['nom' => 'ASC'])
                : [];

            $form->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'placeholder' => '-- Sélectionner une ville --',
                'label' => 'Ville',
                'required' => false,
                'choices' => $villes,
                'attr' => ['class' => 'ville-select'],
            ]);
        };

        // Au chargement du formulaire (edit ou new)
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formModifier) {
            $departement = $event->getData()?->getDepartement();
            $formModifier($event->getForm(), $departement);
        });

        // À la soumission du formulaire
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier) {
            $data = $event->getData();
            $departementId = $data['departement'] ?? null;

            $departement = $departementId
                ? $this->em->getReference(Departement::class, $departementId)
                : null;

            $formModifier($event->getForm(), $departement);
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CartePostale::class,
        ]);
    }
}
