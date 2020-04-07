<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Service;

use JsonFormBuilder\JsonForm\FormTextElement;
use JsonFormBuilderBundle\Excepion\NoFormTypeFound;
use JsonFormBuilderBundle\Form\JsonForm\FormTextElement\ContainerType;
use JsonFormBuilderBundle\Form\JsonForm\FormTextElement\Headline1Type;
use JsonFormBuilderBundle\Form\JsonForm\FormTextElement\Headline2Type;
use JsonFormBuilderBundle\Form\JsonForm\FormTextElement\Headline3Type;
use JsonFormBuilderBundle\Form\JsonForm\FormTextElement\Headline4Type;
use JsonFormBuilderBundle\Form\JsonForm\FormTextElement\Headline5Type;
use JsonFormBuilderBundle\Form\JsonForm\FormTextElement\Headline6Type;
use JsonFormBuilderBundle\Form\JsonForm\FormTextElement\ParagraphType;
use JsonFormBuilderBundle\Form\JsonForm\FormTextElement\QuoteType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormTextElementTypeFactory
{
    private const MAP = [
        FormTextElement\Headline1::class => Headline1Type::class,
        FormTextElement\Headline2::class => Headline2Type::class,
        FormTextElement\Headline3::class => Headline3Type::class,
        FormTextElement\Headline4::class => Headline4Type::class,
        FormTextElement\Headline5::class => Headline5Type::class,
        FormTextElement\Headline6::class => Headline6Type::class,
        FormTextElement\Paragraph::class => ParagraphType::class,
        FormTextElement\Quote::class => ContainerType::class,
        FormTextElement\Container::class => QuoteType::class,
    ];

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createByFormTextElement(FormTextElement $formTextElement, array $options = []): FormInterface
    {
        $class = get_class($formTextElement);

        if (false === array_key_exists($class, self::MAP)) {
            throw NoFormTypeFound::forFormTextElementType($class);
        }

        $formType = self::MAP[$class];

        return $this->factory->create($formType, $formTextElement, array_merge([
            'position' => $formTextElement->position()
        ], $options));
    }

    public function createByFormFieldClass(string $formTextElementClass, array $options): FormInterface
    {
        if (false === array_key_exists($formTextElementClass, self::MAP)) {
            throw  NoFormTypeFound::forFormTextElementType($formTextElementClass);
        }

        $formType = self::MAP[$formTextElementClass];

        return $this->factory->create($formType, null, $options);
    }
}
