<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationListNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, $format = null, array $context = array()): array
    {
		
		$violations_by_field = $this->normalizer->normalize($object, $format, $context);
		
		$response = [];
		$response["errors"] = [];

		foreach ($violations_by_field as $violations) {
			foreach ($violations as $violation) {
				$response["errors"][] = $violation["message"];
			}
		}

		return $response;
		
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof ConstraintViolationListInterface;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
