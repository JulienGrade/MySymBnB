<?php

namespace App\Form;

use App\Entity\Ad;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends ApplicationType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', \Symfony\Component\Form\Extension\Core\Type\TextType::class, $this->getConfiguration("Titre", "Saisissez le titre de votre annonce..."))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse web", "Tapez l'adresse web (automatique)", [
                    'required' => false
                ])
            )
            ->add('coverImage', UrlType::class, $this->getConfiguration("URL de l'image principale", "Donnez l'adresse d'une image qui donne vraiment envie"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Donnez une description globale de l'annonce"))
            ->add('content', TextareaType::class, $this->getConfiguration("Description détaillée", "Saisissez la descritpion de votre annonce"))
            ->add('rooms', IntegerType::class, $this->getConfiguration("Nombre de chambres", "Indiquez le nombre de chambre du logement"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix par nuit", "Indiquez le prix pour une nuit"))
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
