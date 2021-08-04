<?php

namespace  Setrest\OAPIDocumentation\OpenapiFile;

use Setrest\OAPIDocumentation\OpenapiFile\Interfaces\OAPISectionInterface;
use Setrest\OAPIDocumentation\Router\ResponseSpec;
use Illuminate\Support\Str;

class OAPIPath implements OAPISectionInterface
{
    protected $summary = "";

    protected $path;

    protected $method;

    protected $tags;

    protected $parameters;

    protected $responses;

    protected $requestBody;

    public function __construct(string $path, string $summary, string $method, array $tags = null)
    {
        $this->path = $path;
        $this->summary = $summary;
        $this->method = $method;
        $this->parameters = [];
        $this->tags = $tags ?? ['Common']; 
    }

    public function addParameter(string $name, string $type, bool $required = true, string $description = "", string $example = '')
    {
        $this->parameters[] = new OAPIPathParameter($name, $type, $required, $example, $description);
        return $this;
    }

    public function addParameterFromValidation(string $name, string $rule): self
    {
        $this->parameters[] = OAPIPathParameter::parseFromValidation($name, $rule)->toArray();
        return $this;
    }

    public function addResponseFromSpecs(ResponseSpec $response): self
    {
        $oapiResponse = new OAPIPathResponse($response->getCode(), $response->getDescription());

        if ($properties = $response->getProperties()) {
            foreach ($properties as $property) {
                $oapiResponse->addProperty($property['name'], $property['type'], $property['description']);
            }
        }

        $this->responses = $oapiResponse->toArray();

        return $this;
    }

    public function toArray(): array
    {
        return [
            'tags' => $this->tags,
            'summary' => $this->summary,
            'parameters' => $this->parameters,
            'responses' => $this->responses
        ];   
    }

    public static function initFromConfig()
    {
        return null;
    }

    public function getPath(): string
    {
        if ($this->path[0] !== '/') {
            $this->path = '/' . $this->path;
        }

        return $this->path;
    }

    public function getMethod(): string
    {
        return Str::lower($this->method);
    }
}