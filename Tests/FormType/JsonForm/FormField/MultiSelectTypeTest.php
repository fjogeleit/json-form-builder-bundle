<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Tests\FormType\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\MultiSelect;
use JsonFormBuilder\JsonForm\FormField\Option;
use JsonFormBuilderBundle\Form\JsonForm\FormField\MultiSelectType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class MultiSelectTypeTest extends TypeTestCase
{
    public function test_create_select_type(): void
    {
        $form = $this->factory->create(MultiSelectType::class, null, ['position' => 1]);

        $this->assertInstanceOf(FormInterface::class, $form);
    }

    public function test_submit_select_type(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $form = $this->factory->create(MultiSelectType::class, null, ['position' => 1]);

        $form->submit([
            'label' => 'MultiSelect',
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

        /** @var MultiSelect $select */
        $select = $form->getData();

        $this->assertEquals($uuid, $select->formFieldId());
        $this->assertEquals('MultiSelect', $select->label());
        $this->assertEquals(1, $select->position());
        $this->assertFalse($select->required());
        $this->assertTrue($select->visible());

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
