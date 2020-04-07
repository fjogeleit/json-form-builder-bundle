<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Tests\FormType\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\Input;
use JsonFormBuilder\JsonForm\FormField\InputType as InputValueType;
use JsonFormBuilderBundle\Form\JsonForm\FormField\InputType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class InputTypeTest extends TypeTestCase
{
    public function test_create_input_type(): void
    {
        $form = $this->factory->create(InputType::class, null, ['position' => 1]);

        $this->assertInstanceOf(FormInterface::class, $form);
    }

    public function test_submit_input_type(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $form = $this->factory->create(InputType::class, null, ['position' => 1]);

        $form->submit([
            'label' => 'Input',
            'formFieldId' => $uuid,
            'position' => 1,
            'inputType' => InputValueType::TEXT,
            'required' => false,
            'visible' => true
        ]);

        $this->assertTrue($form->isSynchronized());

        /** @var Input $input */
        $input = $form->getData();

        $this->assertEquals($uuid, $input->formFieldId());
        $this->assertEquals('Input', $input->label());
        $this->assertEquals(1, $input->position());
        $this->assertEquals(InputValueType::TEXT, $input->inputType()->toString());
        $this->assertFalse($input->required());
        $this->assertTrue($input->visible());
        $this->assertNull($input->placeholder());
    }
}
