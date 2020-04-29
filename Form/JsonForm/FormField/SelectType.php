<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\Option;
use JsonFormBuilder\JsonForm\FormField\OptionCollection;
use JsonFormBuilder\JsonForm\FormField\Select;
use JsonFormBuilderBundle\Form\JsonForm\FormFieldTypeInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectType extends AbstractType implements FormFieldTypeInterface, DataMapperInterface
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
                'data_class' => Select::class,
                'empty_data' => new Select(
                    Uuid::uuid4()->toString(),
                    '',
                    0,
                    OptionCollection::emptyList()
                )
            ]);
    }

    /**
     * @param Select   $viewData
     * @param iterable $forms
     */
    public function mapDataToForms($viewData, $forms)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        if (false === $viewData instanceof Select) {
            $forms['formFieldId']->setData(Uuid::uuid4()->toString());
            $forms['options']->setData(OptionCollection::emptyList()->add(new Option('', '')));
            $forms['visible']->setData(true);

            return;
        }

        $forms['formFieldId']->setData($viewData->formFieldId());
        $forms['label']->setData($viewData->label());
        $forms['required']->setData($viewData->required());
        $forms['visible']->setData($viewData->visible());
        $forms['options']->setData($viewData->options());
    }

    /**
     * @param iterable $forms
     * @param Select   $viewData
     */
    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $formFieldId = $forms['formFieldId']->getData();

        if (true === empty($formFieldId)) {
            $formFieldId = Uuid::uuid4()->toString();
        }

        $viewData = new Select(
            $formFieldId,
            $forms['label']->getData(),
            (int)$forms['position']->getData(),
            $forms['options']->getData(),
            $forms['required']->getData(),
            $forms['visible']->getData()
        );
    }

    public function getParent(): string
    {
        return CollectionFormFieldType::class;
    }

    public function getFormField(): string
    {
        return Select::class;
    }
}
