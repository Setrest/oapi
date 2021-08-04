<?php

namespace Setrest\OAPIDocumentation\Router;

use Setrest\OAPIDocumentation\Router\ResponseFinders\ArrayFinder;

class ResponseSpec
{
    const DEFAULT_FINDERS = [
        ArrayFinder::class
    ];

    private $code;

    private $description;

    private $properties;

    public function __construct(int $code = 200, string $description = "")
    {
        $this->code = $code;
        $this->description = $description;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getProperties(): ?array
    {
        return $this->properties;
    }

    public function addCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function addDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function addProperty(string $property, string $type = "string", string $description = "")
    {
        $this->properties[] = [
            'name' => $property,
            'type' => $type,
            'description' => $description
        ];
    }

    public static function findByResponses(array $returnCode, array $methodCode): ResponseSpec
    {
        $finder = null;
        $finders = config('oapidocs.responses') ?: self::DEFAULT_FINDERS;

        foreach ($finders as $one) {
            if ($finder === null) {
                $finder = new $one;
                continue;
            }

            $finder->setNext(new $one);
        } 

        $response = $finder->find($returnCode, $methodCode);

        if (!$response) {
            $response = new ResponseSpec(200, 'Success request!');
        }

        return $response;
    }
}