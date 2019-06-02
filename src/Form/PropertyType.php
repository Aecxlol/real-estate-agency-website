<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price')
            ->add('heat', ChoiceType::class, [
                'choices' => $this->getChoices()
            ])
            ->add('city')
            ->add('postalCode')
            ->add('sold', CheckboxType::class, [
                'required' => false
            ])
            ->add('address')
        ;
    }

    //sert à définir les options du form de manière globale
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain' => 'forms'
        ]);
    }

    //fonction qui inverse la clé et la valeur pour bien avoir le choix des chauffages et non leur id
    private function getChoices()
    {
        $choices = Property::HEAT;
        $output = [];
        foreach($choices as $k => $v)
        {
            $output[$v] = $k;
        }
        return $output;
    }
}
