<?php

namespace  Setrest\OAPIDocumentation\OpenapiFile;

use Setrest\OAPIDocumentation\OpenapiFile\Interfaces\OAPISectionInterface;
use Setrest\OAPIDocumentation\Router\ResponseSpec;
use Illuminate\Support\Str;

class OAPIPath implements OAPISectionInterface
{
    /**
     * @var string
     */
    protected $summary = "";

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $tags;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var ResponseSpec
     */
    protected $responses;

    public function __construct(string $path, string $summary, string $method, array $tags = null)
    {
        $this->path = $path;
        $this->summary = $summary;
        $this->method = $method;
        $this->tags = $tags ?? ['Common']; 
    }

    /**
     * Added path parameter
     *
     * @param string $name
     * @param string $type
     * @param boolean $required
     * @param string $description
     * @param string $example
     * @return self
     */
    public function addParameter(string $name, string $type, bool $required = true, string $description = "", string $example = ''): self
    {
        $this->parameters[] = new OAPIPathParameter($name, $type, $required, $example, $description);
        return $this;
    }

    /**
     * Parsing rules from validation
     *
     * @param string $name
     * @param string $rule
     * @return self
     */
    public function addParameterFromValidation(string $name, string $rule): self
    {
        $this->parameters[] = OAPIPathParameter::parseFromValidation($name, $rule)->toArray();
        return $this;
    }

    /**
     * Adding a response to the path from the searchers.
     *
     * @param ResponseSpec $response
     * @return self
     */
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

    /**
     * Converting to array from data of object. 
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'tags' => $this->tags,
            'summary' => $this->summary,
            'parameters' => $this->parameters,
            'responses' => $this->responses
        ];   
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        if ($this->path[0] !== '/') {
            $this->path = '/' . $this->path;
        }

        return $this->path;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return Str::lower($this->method);
    }

    public static function initFromConfig()
    {
        return null;
    }
}