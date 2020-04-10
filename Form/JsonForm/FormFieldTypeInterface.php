<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm;

use Symfony\Component\Form\FormTypeInterface;

interface FormFieldTypeInterface extends FormTypeInterface
{
    public function getFormField(): string;
}
