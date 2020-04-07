<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Service;

use JsonFormBuilder\JsonForm\FormField;
use JsonFormBuilder\JsonForm\FormField\Checkbox;
use JsonFormBuilder\JsonForm\FormField\CheckboxGroup;
use JsonFormBuilder\JsonForm\FormField\Input;
use JsonFormBuilder\JsonForm\FormField\MultiSelect;
use JsonFormBuilder\JsonForm\FormField\RadioButton;
use JsonFormBuilder\JsonForm\FormField\RadioButtonGroup;
use JsonFormBuilder\JsonForm\FormField\Select;
use JsonFormBuilder\JsonForm\FormField\TextArea;
use JsonFormBuilderBundle\Excepion\NoFormTypeFound;
use JsonFormBuilderBundle\Form\JsonForm\FormField\CheckboxGroupType;
use JsonFormBuilderBundle\Form\JsonForm\FormField\CheckboxType;
use JsonFormBuilderBundle\Form\JsonForm\FormField\InputType;
use JsonFormBuilderBundle\Form\JsonForm\FormField\MultiSelectType;
use JsonFormBuilderBundle\Form\JsonForm\FormField\RadioButtonGroupType;
use JsonFormBuilderBundle\Form\JsonForm\FormField\RadioButtonType;
use JsonFormBuilderBundle\Form\JsonForm\FormField\SelectType;
use JsonFormBuilderBundle\Form\JsonForm\FormField\TextAreaType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormFieldTypeFactory
{
    private const MAP = [
        Checkbox::class => CheckboxType::class,
        CheckboxGroup::class => CheckboxGroupType::class,
        RadioButton::class => RadioButtonType::class,
        RadioButtonGroup::class => RadioButtonGroupType::class,
        Select::class => SelectType::class,
        MultiSelect::class => MultiSelectType::class,
        Input::class => InputType::class,
        TextArea::class => TextAreaType::class,
    ];

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createByFormField(FormField $formField, array $options): FormInterface
    {
        $class = get_class($formField);

        if (false === array_key_exists($class, self::MAP)) {
            throw  NoFormTypeFound::forFormField($class);
        }

        $formType = self::MAP[$class];

        return $this->factory->create($formType, $formField, array_merge([
            'position' => $formField->position()
        ], $options));
    }

    public function createByFormFieldClass(string $formField, array $options): FormInterface
    {
        if (false === array_key_exists($formField, self::MAP)) {
            throw  NoFormTypeFound::forFormField($formField);
        }

        $formType = self::MAP[$formField];

        return $this->factory->create($formType, null, $options);
    }
}
