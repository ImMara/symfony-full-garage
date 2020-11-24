<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CatalogueType extends AbstractType
{
    private function getConfiguration($label,$placeholder){
        return [
            'label'=>$label,
            'attr'=> [
                'placeholder'=>$placeholder
            ]
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marque',TextType::class,$this->getConfiguration('marque','Marque de votre voiture'))
            ->add('modele',TextType::class,$this->getConfiguration('modele','Modéle de votre voiture'))
            ->add('cover',UrlType::class,$this->getConfiguration('url de l\'image','Veuillez entrez l\'adresse de votre image'))
            ->add('km',IntegerType::class,$this->getConfiguration('Nombre de km','km au compteur'))
            ->add('prix',MoneyType::class, $this->getConfiguration('Prix de votre voiture','indiquer le prix que vous voulez'))
            ->add('proprios',IntegerType::class,$this->getConfiguration('Nombre de proprietaire','indiquez un nombre'))
            ->add('cylindre',IntegerType::class,$this->getConfiguration('Cylindrée','cylindrée moteur de votre voiture'))
            ->add('puissance',IntegerType::class,$this->getConfiguration('Puissance en chevaux','exemple : 450'))
            ->add('carburant',TextType::class,$this->getConfiguration('Carburant','essance ou diesel?'))
            ->add('dateMiseCirculation',DateType::class,$this->getConfiguration('Date de premiere mise en circulation',''))
            ->add('transmission')
            ->add('description',TextareaType::class, $this->getConfiguration('Description','Déscription détaillée de votre voiture'))
            ->add('options',TextareaType::class,$this->getConfiguration('Option de votre voiture','vos options ici'))
            ->add('slug',TextType::class, $this->getConfiguration('Slug','Adresse web (automatique)'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }

}
