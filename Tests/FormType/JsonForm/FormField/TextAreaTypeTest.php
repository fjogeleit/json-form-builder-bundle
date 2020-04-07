<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Tests\FormType\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\TextArea;
use JsonFormBuilderBundle\Form\JsonForm\FormField\TextAreaType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class TextAreaTypeTest extends TypeTestCase
{
    public function test_create_input_type(): void
    {
        $form = $this->factory->create(TextAreaType::class, null, ['position' => 1]);

        $this->assertInstanceOf(FormInterface::class, $form);
    }

    public function test_submit_input_type(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $form = $this->factory->create(TextAreaType::class, null, ['position' => 1]);

        $form->submit([
            'label' => 'TextArea',
            'formFieldId' => $uuid,
            'position' => 1,
            'required' => false,
            'visible' => true
        ]);

        $this->assertTrue($form->isSynchronized());

        /** @var TextArea $textArea */
        $textArea = $form->getData();

        $this->assertEquals($uuid, $textArea->formFieldId());
        $this->assertEquals('TextArea', $textArea->label());
        $this->assertEquals(1, $textArea->position());
        $this->assertFalse($textArea->required());
        $this->assertTrue($textArea->visible());
        $this->assertNull($textArea->placeholder());
    }
}
