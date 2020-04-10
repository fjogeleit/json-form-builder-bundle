<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Tests\FormType\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\Option;
use JsonFormBuilder\JsonForm\FormField\OptionCollection;
use JsonFormBuilderBundle\Form\JsonForm\SimpleOptionCollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class SimpleOptionCollectionTypeTest extends TypeTestCase
{
    public function test_create_option_collection_type(): void
    {
        $form = $this->factory->create(
            SimpleOptionCollectionType::class,
            OptionCollection::emptyList()
                ->add(new Option('Label A', 'Label A'))
                ->add(new Option('Label B', 'Label A'))
        );

        $this->assertInstanceOf(FormInterface::class, $form);
        $this->assertInstanceOf(OptionCollection::class, $form->getData());

        $this->assertFormIndex($form, 0, 'Label A', 'Label A');
        $this->assertFormIndex($form, 1, 'Label B', 'Label A');
    }

    public function test_submit_option_collection_type(): void
    {
        $form = $this->factory->create(SimpleOptionCollectionType::class, OptionCollection::emptyList()->add(new Option('Label P', 'Label P')));
        $form->submit([
            ['value' => 'Label A'],
            ['value' => 'Label B'],
            ['value' => 'Label C'],
            ['value' => 'Label D'],
        ]);

        $this->assertTrue($form->isSynchronized());

        $this->assertFormIndex($form, 0, 'Label A', 'Label A');
        $this->assertFormIndex($form, 1, 'Label B', 'Label B');
        $this->assertFormIndex($form, 2, 'Label C', 'Label C');
        $this->assertFormIndex($form, 3, 'Label D', 'Label D');
    }

    public function assertFormIndex(FormInterface $form, int $index, string $label, string $value)
    {
        $this->assertInstanceOf(FormInterface::class, $form->get((string)$index));
        $this->assertInstanceOf(Option::class, $form->get((string)$index)->getData());
        $this->assertEquals($label, $form->get((string)$index)->getData()->label());
        $this->assertEquals($value, $form->get((string)$index)->getData()->value());
    }
}
