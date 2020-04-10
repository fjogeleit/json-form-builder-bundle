<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm;

use JsonFormBuilder\JsonForm\FormField\Option;
use JsonFormBuilder\JsonForm\FormField\OptionCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionCollectionType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => OptionCollection::class,
                'entry_type' => OptionType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'translation_domain' => 'json_form_builder',
                'empty_data' => OptionCollection::emptyList()
            ]);
    }

    /**
     * @param array    $viewData
     * @param iterable $forms
     */
    public function mapDataToForms($viewData, $forms)
    {
        if (false === $viewData instanceof OptionCollection) {
            return;
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        foreach ($forms as $key => $form) {
            $form->setData($viewData[$key] ?? null);
        }
    }

    /**
     * @param iterable $forms
     * @param Option   $viewData
     */
    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $viewData = OptionCollection::emptyList();

        foreach ($forms as $form) {
            $viewData = $viewData->add($form->getData());
        }
    }

    public function getParent(): string
    {
        return CollectionType::class;
    }
}
