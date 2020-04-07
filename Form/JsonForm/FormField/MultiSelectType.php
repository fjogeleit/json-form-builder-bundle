<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\MultiSelect;
use JsonFormBuilder\JsonForm\FormField\OptionCollection;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultiSelectType extends AbstractType implements DataMapperInterface
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
                'data_class' => MultiSelect::class,
                'empty_data' => new MultiSelect(
                    Uuid::uuid4()->toString(),
                    '',
                    0,
                    OptionCollection::emptyList()
                )
            ]);
    }

    /**
     * @param MultiSelect $viewData
     * @param iterable    $forms
     */
    public function mapDataToForms($viewData, $forms)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        if (false === $viewData instanceof MultiSelect) {
            $forms['formFieldId']->setData(Uuid::uuid4()->toString());

            return;
        }

        $forms['formFieldId']->setData($viewData->formFieldId());
        $forms['label']->setData($viewData->label());
        $forms['required']->setData($viewData->required());
        $forms['visible']->setData($viewData->visible());
    }

    /**
     * @param iterable    $forms
     * @param MultiSelect $viewData
     */
    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $formFieldId = $forms['formFieldId']->getData();

        if (true === empty($formFieldId)) {
            $formFieldId = Uuid::uuid4()->toString();
        }

        $viewData = new MultiSelect(
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
}
