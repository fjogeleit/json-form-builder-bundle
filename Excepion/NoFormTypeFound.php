<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Excepion;

final class NoFormTypeFound extends \InvalidArgumentException
{
    public static function forFormField(string $formField): self
    {
        return new self(sprintf('No FormType found for the given FormField "%s"', $formField));
    }

    public static function forFormTextElementType(string $formTextElement): self
    {
        return new self(sprintf('No FormType found for the given FormTextElement "%s"', $formTextElement));
    }
}
