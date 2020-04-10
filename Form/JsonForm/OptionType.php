<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm;

use JsonFormBuilder\JsonForm\FormField\Option;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, ['required' => true, 'translation_domain' => 'json_form_builder'])
            ->add('value', TextType::class, ['required' => true, 'translation_domain' => 'json_form_builder'])
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'label' => false,
                'data_class' => Option::class,
                'empty_data' => new Option('', '')
            ]);
    }

    /**
     * @param Option   $viewData
     * @param iterable $forms
     */
    public function mapDataToForms($viewData, $forms)
    {
        if (false === $viewData instanceof Option) {
            return;
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $forms['label']->setData($viewData->label());
        $forms['value']->setData($viewData->value());
    }

    /**
     * @param iterable $forms
     * @param Option   $viewData
     */
    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $viewData = new Option(
            $forms['label']->getData(),
            $forms['value']->getData()
        );
    }
}
