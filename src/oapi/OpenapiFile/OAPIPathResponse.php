<?php

namespace  Setrest\OAPIDocumentation\OpenapiFile;

class OAPIPathResponse
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var array
     */
    protected $properties;

    public function __construct(int $code = 200, string $description = 'Success request!')
    {
        $this->code = $code;
        $this->description = $description;
    }

    /**
     * Adding a property to the response path.
     *
     * @param string $property
     * @param string $type
     * @param string $description
     * @return self
     */
    public function addProperty(string $property, string $type = "string", string $description = ""): self
    {
        $this->properties[] = [
            'name' => $property,
            'type' => $type,
            'description' => $description
        ];

        return $this;
    }

    /**
     * Converting to array from data of object. 
     * Returns array with:
     * - description
     * - content|null
     * -- application/json
     * --- schema
     * ---- properties
     * 
     * @return array
     */
    public function toArray(): array
    {
        $properties = [];
        if (!$this->properties) {
            return [
                $this->code => [
                    'description' => $this->description
                ]
            ];
        }

        foreach ($this->properties as $property) {
            $properties[$property['name']] = [
                'type' => $property['type'],
                'description' => $property['description'],
            ];
        }
        
        return [
            $this->code => [
                'description' => $this->description,
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'properties' => $properties
                        ]
                    ]
                ]
            ]
        ];
    }
}