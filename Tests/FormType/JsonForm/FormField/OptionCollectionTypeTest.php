<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Tests\FormType\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\Option;
use JsonFormBuilder\JsonForm\FormField\OptionCollection;
use JsonFormBuilderBundle\Form\JsonForm\FormField\OptionCollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class OptionCollectionTypeTest extends TypeTestCase
{
    public function test_create_option_collection_type(): void
    {
        $form = $this->factory->create(
            OptionCollectionType::class,
            OptionCollection::emptyList()
                ->add(new Option('Label A', 'A'))
                ->add(new Option('Label B', 'B'))
        );

        $this->assertInstanceOf(FormInterface::class, $form);
        $this->assertInstanceOf(OptionCollection::class, $form->getData());

        $this->assertFormIndex($form, 0, 'Label A', 'A');
        $this->assertFormIndex($form, 1, 'Label B', 'B');
    }

    public function test_submit_option_collection_type(): void
    {
        $form = $this->factory->create(OptionCollectionType::class, OptionCollection::emptyList()->add(new Option('Label P', 'P')));
        $form->submit([
            ['label' => 'Label A', 'value' => 'A'],
            ['label' => 'Label B', 'value' => 'B'],
            ['label' => 'Label C', 'value' => 'C'],
            ['label' => 'Label D', 'value' => 'D'],
        ]);

        $this->assertTrue($form->isSynchronized());

        $this->assertFormIndex($form, 0, 'Label A', 'A');
        $this->assertFormIndex($form, 1, 'Label B', 'B');
        $this->assertFormIndex($form, 2, 'Label C', 'C');
        $this->assertFormIndex($form, 3, 'Label D', 'D');
    }

    public function assertFormIndex(FormInterface $form, int $index, string $label, string $value)
    {
        $this->assertInstanceOf(FormInterface::class, $form->get((string)$index));
        $this->assertInstanceOf(Option::class, $form->get((string)$index)->getData());
        $this->assertEquals($label, $form->get((string)$index)->getData()->label());
        $this->assertEquals($value, $form->get((string)$index)->getData()->value());
    }
}
