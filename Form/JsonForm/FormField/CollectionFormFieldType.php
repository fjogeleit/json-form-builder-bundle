<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm\FormField;

use JsonFormBuilderBundle\Form\JsonForm\FormFieldType;
use JsonFormBuilderBundle\Form\JsonForm\OptionCollectionType;
use JsonFormBuilderBundle\Form\JsonForm\SimpleOptionCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionFormFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $optionsType = OptionCollectionType::class;

        if (true === $options['simple_options']) {
            $optionsType = SimpleOptionCollectionType::class;
        }

        $builder
            ->add('options', $optionsType, ['translation_domain' => 'json_form_builder', 'label' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['simple_options' => true]);
    }

    public function getParent(): string
    {
        return FormFieldType::class;
    }
}
