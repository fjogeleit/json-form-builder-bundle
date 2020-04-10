<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\Checkbox;
use JsonFormBuilderBundle\Form\JsonForm\FormFieldType;
use JsonFormBuilderBundle\Form\JsonForm\FormFieldTypeInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckboxType extends AbstractType implements FormFieldTypeInterface, DataMapperInterface
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
                'data_class' => Checkbox::class,
                'empty_data' => new Checkbox(Uuid::uuid4()->toString(), '', 0)
            ]);
    }

    /**
     * @param Checkbox $viewData
     *
     * @param iterable $forms
     */
    public function mapDataToForms($viewData, $forms)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        if (false === $viewData instanceof Checkbox) {
            $forms['formFieldId']->setData(Uuid::uuid4()->toString());

            return;
        }

        $forms['formFieldId']->setData($viewData->formFieldId());
        $forms['label']->setData($viewData->label());
        $forms['required']->setData($viewData->required());
        $forms['visible']->setData($viewData->visible());
    }

    /**
     * @param iterable $forms
     *
     * @param Checkbox $viewData
     */
    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $formFieldId = $forms['formFieldId']->getData();

        if (true === empty($formFieldId)) {
            $formFieldId = Uuid::uuid4()->toString();
        }

        $viewData = new Checkbox(
            $formFieldId,
            $forms['label']->getData(),
            (int)$forms['position']->getData(),
            $forms['required']->getData(),
            $forms['visible']->getData()
        );
    }

    public function getParent(): string
    {
        return FormFieldType::class;
    }

    public function getFormField(): string
    {
        return Checkbox::class;
    }
}
