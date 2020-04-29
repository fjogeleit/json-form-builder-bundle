<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\Input;
use JsonFormBuilder\JsonForm\FormField\InputType as InputValueType;
use JsonFormBuilderBundle\Form\JsonForm\FormFieldType;
use JsonFormBuilderBundle\Form\JsonForm\FormFieldTypeInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InputType extends AbstractType implements FormFieldTypeInterface, DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $placeholderType = TextType::class;

        if (false === $options['with_placeholder']) {
            $placeholderType = HiddenType::class;
        }

        $builder
            ->add('inputType', HiddenType::class, ['required' => true, 'empty_data' => $options['type'], 'label' => false])
            ->add('placeholder', $placeholderType, ['translation_domain' => 'json_form_builder'])
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'with_placeholder' => true,
                'data_class' => Input::class,
                'type' => InputValueType::TEXT,
                'empty_data' => new Input(
                    Uuid::uuid4()->toString(),
                    '',
                    0
                )
            ]);
    }

    /**
     * @param Input    $viewData
     * @param iterable $forms
     */
    public function mapDataToForms($viewData, $forms)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        if (false === $viewData instanceof Input) {
            $forms['formFieldId']->setData(Uuid::uuid4()->toString());
            $forms['visible']->setData(true);

            return;
        }

        $forms['formFieldId']->setData($viewData->formFieldId());
        $forms['label']->setData($viewData->label());
        $forms['required']->setData($viewData->required());
        $forms['visible']->setData($viewData->visible());
    }

    /**
     * @param iterable $forms
     * @param Input    $viewData
     */
    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $formFieldId = $forms['formFieldId']->getData();

        if (true === empty($formFieldId)) {
            $formFieldId = Uuid::uuid4()->toString();
        }

        $viewData = new Input(
            $formFieldId,
            $forms['label']->getData(),
            (int)$forms['position']->getData(),
            InputValueType::fromString($forms['inputType']->getData()),
            $forms['required']->getData(),
            $forms['visible']->getData(),
            $forms['placeholder']->getData()
        );
    }

    public function getParent(): string
    {
        return FormFieldType::class;
    }

    public function getFormField(): string
    {
        return Input::class;
    }
}
