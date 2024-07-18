<?php

namespace App\Form;

use App\Entity\Marque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label' => 'Nom de la marque',
                'required' => true,
            ])
            ->add('slug',TextType::class, [
                'label' => 'Slug de la marque',
                'required' => true,
            ])
            ->add('description',TextareaType::class, [
                'label' => 'Description de la marque',
                'required' => false,
            ])
            ->add('imageName',TextType::class, [
                'label' => 'Image de la marque',
                'required' => false,
            ])
            ->add('enable',CheckboxType::class,[
                'label' => 'Activer la marque',
                'required' => false,
            ]) ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Marque::class,
        ]);
    }
}
