<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Tests\FormType\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\CheckboxGroup;
use JsonFormBuilder\JsonForm\FormField\Option;
use JsonFormBuilderBundle\Form\JsonForm\FormField\CheckboxGroupType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class CheckboxGroupTypeTest extends TypeTestCase
{
    public function test_create_checkbox_group_type(): void
    {
        $form = $this->factory->create(CheckboxGroupType::class, null, ['position' => 1]);

        $this->assertInstanceOf(FormInterface::class, $form);
    }

    public function test_submit_checkbox_group_type(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $form = $this->factory->create(CheckboxGroupType::class, null, ['position' => 1, 'simple_options' => false]);

        $form->submit([
            'label' => 'CheckboxGroup',
            'formFieldId' => $uuid,
            'position' => 1,
            'required' => false,
            'visible' => true,
            'options' => [
                ['label' => 'Label A', 'value' => 'A'],
                ['label' => 'Label B', 'value' => 'B'],
            ]
        ]);

        $this->assertTrue($form->isSynchronized());

        /** @var CheckboxGroup $checkboxGroup */
        $checkboxGroup = $form->getData();

        $this->assertEquals($uuid, $checkboxGroup->formFieldId());
        $this->assertEquals('CheckboxGroup', $checkboxGroup->label());
        $this->assertEquals(1, $checkboxGroup->position());
        $this->assertFalse($checkboxGroup->required());
        $this->assertTrue($checkboxGroup->visible());

        $this->assertFormIndex($form->get('options'), 0, 'Label A', 'A');
        $this->assertFormIndex($form->get('options'), 1, 'Label B', 'B');
    }

    public function assertFormIndex(FormInterface $form, int $index, string $label, string $value)
    {
        $this->assertInstanceOf(FormInterface::class, $form->get((string)$index));
        $this->assertInstanceOf(Option::class, $form->get((string)$index)->getData());
        $this->assertEquals($label, $form->get((string)$index)->getData()->label());
        $this->assertEquals($value, $form->get((string)$index)->getData()->value());
    }
}
