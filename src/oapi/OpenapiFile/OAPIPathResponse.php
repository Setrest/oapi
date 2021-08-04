<?php

namespace  Setrest\OAPIDocumentation\OpenapiFile;

class OAPIPathResponse
{
    protected $code;

    protected $description;

    protected $properties;

    public function __construct(int $code = 200, string $description = 'Success request!')
    {
        $this->code = $code;
        $this->description = $description;
    }

    public function addProperty(string $property, string $type = "string", string $description = "")
    {
        $this->properties[] = [
            'name' => $property,
            'type' => $type,
            'description' => $description
        ];
    }

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