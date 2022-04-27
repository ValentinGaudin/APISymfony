<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;


class APISerializer
{
    public function toJSON($data)
    {
        return $jsonContent = $this->serializer()->serialize($data, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
    }

    public function response($jsonContent): Response
    {
        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    protected function serializer()
    {
        return $serializer = new Serializer($this->getNormalizers(), $this->getEncoder());
    }

    protected function getEncoder()
    {
        return $encoders = [new JsonEncoder()];
    }

    protected function getNormalizers() 
    {
        return $normalizers = [new ObjectNormalizer()];
    }

}