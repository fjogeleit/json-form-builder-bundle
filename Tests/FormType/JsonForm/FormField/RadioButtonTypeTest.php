<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Tests\FormType\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\RadioButton;
use JsonFormBuilderBundle\Form\JsonForm\FormField\RadioButtonType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class RadioButtonTypeTest extends TypeTestCase
{
    public function test_create_radio_button_type(): void
    {
        $form = $this->factory->create(RadioButtonType::class, null, ['position' => 1]);

        $this->assertInstanceOf(FormInterface::class, $form);
    }

    public function test_submit_radio_button_type(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $form = $this->factory->create(RadioButtonType::class, null, ['position' => 1]);

        $form->submit([
            'label' => 'RadioButton',
            'formFieldId' => $uuid,
            'position' => 1,
            'required' => false,
            'visible' => true
        ]);

        $this->assertTrue($form->isSynchronized());

        /** @var RadioButton $radioButton */
        $radioButton = $form->getData();

        $this->assertEquals($uuid, $radioButton->formFieldId());
        $this->assertEquals('RadioButton', $radioButton->label());
        $this->assertEquals(1, $radioButton->position());
        $this->assertFalse($radioButton->required());
        $this->assertTrue($radioButton->visible());
    }
}
