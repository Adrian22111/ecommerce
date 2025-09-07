<?php

namespace App\Form\Admin;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\ProductCategory;
use App\Form\Admin\ProductImageForm;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price', MoneyType::class, [
                'divisor' => 100
            ])
            ->add('description', TextareaType::class, [])
            ->add('symbol')
            ->add('categories', EntityType::class, [
                'class' => ProductCategory::class,
                'choice_label' => 'name',
                'multiple' => true,
                'autocomplete' => true
            ])
            ->add('productImages', CollectionType::class, [
                'entry_type' => ProductImageForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $product = $event->getData();

                // jeśli produkt istnieje i nie ma obrazków, dodaj pusty obiekt
                // bo symfony nie zainicjalizuje kolekcji i nie będzie pola do update
                if ($product && $product->getProductImages()->isEmpty()) {
                    $product->addProductImage(new ProductImage());
                }
            })
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,

        ]);
    }
}
