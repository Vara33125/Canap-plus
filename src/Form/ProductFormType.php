<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class)
        ->add('imageFile', VichImageType::class)
        ->add('price', NumberType::class)
        ->add('description', TextareaType::class) 
        ->add('Category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'name',
            ])
        ->add('Tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => true,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
