<?php

namespace Krak\ApiPlatformExtra\Serializer;

use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Serializer\ItemNormalizer;

final class ConstructorResourceItemNormalizer extends ItemNormalizer
{
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if ($res = self::denormalizeIRI($this->iriConverter, $data, $context)) {
            return $res;
        }

        return parent::denormalize($data, $class, $format, $context);
    }

    public static function denormalizeIRI(IriConverterInterface $iriConverter, $data, array $context) {
        if (is_string($data) && !isset($context[ItemNormalizer::OBJECT_TO_POPULATE])) {
            return $iriConverter->getItemFromIri($data, $context + ['fetch_data' => true]);
        }
    }
}
