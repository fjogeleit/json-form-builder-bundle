<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Doctrine\Type;

use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use JsonFormBuilder\JsonForm\FormTextElementCollection;

class FormTextElementCollectionType extends JsonType
{
    const FORM_TEXT_ELEMENT_COLLECTION = 'form_text_element_collection';

    public function getName(): string
    {
        return self::FORM_TEXT_ELEMENT_COLLECTION;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return FormTextElementCollection::fromArray(json_decode($value, true));
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof FormTextElementCollection) {
            $value = json_encode($value);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return ! $platform->hasNativeJsonType();
    }
}
