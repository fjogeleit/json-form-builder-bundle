<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Tests\FormType\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\Checkbox;
use JsonFormBuilderBundle\Form\JsonForm\FormField\CheckboxType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class CheckboxTypeTest extends TypeTestCase
{
    public function test_create_checkbox_type(): void
    {
        $form = $this->factory->create(CheckboxType::class, null, ['position' => 1]);

        $this->assertInstanceOf(FormInterface::class, $form);
    }

    public function test_submit_checkbox_type(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $form = $this->factory->create(CheckboxType::class, null, ['position' => 1]);

        $form->submit([
            'label' => 'Checkbox',
            'formFieldId' => $uuid,
            'position' => 1,
            'required' => false,
            'visible' => true
        ]);

        $this->assertTrue($form->isSynchronized());

        /** @var Checkbox $checkbox */
        $checkbox = $form->getData();

        $this->assertEquals($uuid, $checkbox->formFieldId());
        $this->assertEquals('Checkbox', $checkbox->label());
        $this->assertEquals(1, $checkbox->position());
        $this->assertFalse($checkbox->required());
        $this->assertTrue($checkbox->visible());
    }
}
