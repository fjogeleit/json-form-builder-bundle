<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm;

use Symfony\Component\Form\FormTypeInterface;

interface FormTextElementTypeInterface extends FormTypeInterface
{
    public function getFormTextElement(): string;
}
