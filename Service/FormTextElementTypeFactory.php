<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Service;

use JsonFormBuilder\JsonForm\FormTextElementInterface;
use JsonFormBuilderBundle\Excepion\NoFormTypeFound;
use JsonFormBuilderBundle\Form\JsonForm\FormTextElementTypeInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormTextElementTypeFactory
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

        /** @var FormTextElementTypeInterface $formType */
        foreach ($formTypes as $formType) {
            if (false === $formType instanceof FormTextElementTypeInterface) {
                continue;
            }

            $this->formTypes[$formType->getFormTextElement()] = get_class($formType);
        }
    }

    public function createByFormTextElement(FormTextElementInterface $formTextElement, array $options = []): FormInterface
    {
        $class = get_class($formTextElement);

        if (false === array_key_exists($class, $this->formTypes)) {
            throw NoFormTypeFound::forFormTextElementType($class);
        }

        $formType = $this->formTypes[$class];

        return $this->factory->create($formType, $formTextElement, array_merge([
            'position' => $formTextElement->position()
        ], $options));
    }

    public function createByFormFieldClass(string $formTextElementClass, array $options): FormInterface
    {
        if (false === array_key_exists($formTextElementClass, $this->formTypes)) {
            throw  NoFormTypeFound::forFormTextElementType($formTextElementClass);
        }

        $formType = $this->formTypes[$formTextElementClass];

        return $this->factory->create($formType, null, $options);
    }
}
