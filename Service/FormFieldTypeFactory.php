<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Service;

use JsonFormBuilder\JsonForm\FormFieldInterface;
use JsonFormBuilderBundle\Excepion\NoFormTypeFound;
use JsonFormBuilderBundle\Form\JsonForm\FormFieldTypeInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormFieldTypeFactory
{
    /**
     * @var array|string[]
     */
    private $formTypes = [];

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    public function __construct(FormFactoryInterface $factory, iterable $formTypes)
    {
        $this->factory = $factory;

        /** @var FormFieldTypeInterface $formType */
        foreach ($formTypes as $formType) {
            if (false === $formType instanceof FormFieldTypeInterface) {
                continue;
            }

            $this->formTypes[$formType->getFormField()] = get_class($formType);
        }
    }

    public function createByFormField(FormFieldInterface $formField, array $options): FormInterface
    {
        $class = get_class($formField);

        if (false === array_key_exists($class, $this->formTypes)) {
            throw  NoFormTypeFound::forFormField($class);
        }

        $formType = $this->formTypes[$class];

        return $this->factory->create($formType, $formField, array_merge([
            'position' => $formField->position()
        ], $options));
    }

    public function createByFormFieldClass(string $formField, array $options): FormInterface
    {
        if (false === array_key_exists($formField, $this->formTypes)) {
            throw  NoFormTypeFound::forFormField($formField);
        }

        $formType = $this->formTypes[$formField];

        return $this->factory->create($formType, null, $options);
    }
}
