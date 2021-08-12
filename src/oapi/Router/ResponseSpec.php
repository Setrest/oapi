<?php

namespace Setrest\OAPIDocumentation\Router;

class ResponseSpec
{
    const DEFAULT_FINDERS = [
        \Setrest\OAPIDocumentation\Router\ResponseFinders\ArrayFinder::class
    ];

    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $description;

    /**
     * @var array
     */
    private $properties;

    public function __construct(int $code = 200, string $description = "")
    {
        $this->code = $code;
        $this->description = $description;
    }

    /**
     * Getting code from route response
     *
     * @return integer
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Getting description of response
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Getting array of properties from response
     *
     * @return array|null
     */
    public function getProperties(): ?array
    {
        return $this->properties;
    }

    /**
     * Add code to respones
     *
     * @param integer $code
     * @return self
     */
    public function addCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Add description
     *
     * @param string $description
     * @return self
     */
    public function addDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Add property for response
     *
     * @param string $property
     * @param string $type
     * @param string $description
     * @return void
     */
    public function addProperty(string $property, string $type = "string", string $description = "")
    {
        $this->properties[] = [
            'name' => $property,
            'type' => $type,
            'description' => $description
        ];
    }

    /**
     * Search for an answer by finders objects.
     *
     * @param array $returnCode
     * @param array $methodCode
     * @return ResponseSpec
     */
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