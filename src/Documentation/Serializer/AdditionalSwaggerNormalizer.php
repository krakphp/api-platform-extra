<?php

namespace Krak\ApiPlatformExtra\Documentation\Serializer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class AdditionalSwaggerNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;
    private $swaggerDocs;

    public function __construct(NormalizerInterface $normalizer, array $swaggerDocs) {
        $this->normalizer = $normalizer;
        $this->swaggerDocs = $swaggerDocs;
    }

    public function hasCacheableSupportsMethod(): bool {
        return $this->normalizer instanceof CacheableSupportsMethodInterface && $this->normalizer->hasCacheableSupportsMethod();
    }

    public function normalize($object, $format = null, array $context = array()) {
        $swaggerDocs = $this->normalizer->normalize($object, $format, $context);
        return \array_merge_recursive($swaggerDocs, $this->swaggerDocs);
    }

    public function supportsNormalization($data, $format = null) {
        return $this->normalizer->supportsNormalization($data, $format);
    }

    // stolen from: http://php.net/manual/en/function.array-merge-recursive.php#92195
    private function mergeArrayDistinct($array1, $array2) {
        $merged = $array1;
        foreach ($array2 as $key => $value) {
            if (\is_array($value) && isset($merged[$key]) && \is_array($merged[$key]))  {
                $merged[$key] = $this->mergeArrayDistinct($merged[$key], $value);
            }
            else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
