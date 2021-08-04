<?php

namespace  Setrest\OAPIDocumentation\OpenapiFile;

use BadMethodCallException;
use Error;
use Exception;

class OAPIPathParameter
{
    protected $name;

    protected $in;

    protected $description;

    protected $type;

    protected $example;

    protected $required;

    protected $items;

    protected $nullable;

    protected $max;

    protected $min;

    public function __construct(string $name, string $type = null, bool $required = false, string $example = "", string $description = "", string $in = 'query')
    {
        $this->name = $name;
        $this->type = $type ?? "string";
        $this->required = $required ?? false;
        $this->example = $example ?? "";
        $this->description = $description ?? "";
        $this->in = $in ?? 'query';
        $this->nullable = false;
        $this->max = null;
        $this->min = null;
    }

    public function __call($name, $arguments)
    {
        $stringTypes = [
            'string', 'email', 'date', 'date_format', 'json', 'alpha', 'alpha_dash', 'alpha_numeric',
            'file', 'image', 'ip_address', 'extension', 'timezone', 'url', 'uuid', 'password'
        ];

        $integerTypes = [
            'integer', 'int'
        ];

        $arrayTypes = [
            'array', 'in_array'
        ];

        $booleanTypes = [
            'bool', 'boolean'
        ];
            
        if (in_array($name, $stringTypes)) {
            $this->setType('string');
        } elseif (in_array($name, $integerTypes)) {
            $this->setType('integer');
        } elseif (in_array($name, $arrayTypes)) {
            $this->setType('array');
        } elseif (in_array($name , $booleanTypes)) {
            $this->setType('boolean');
        } elseif ($name === 'numeric') {
            $this->setType('number');
        } else {
            $class = get_class($this);
            throw new BadMethodCallException("No callable method $name at $class class");
        }

        return $this;
    }

    public static function parseFromValidation(string $name, string $rule): self
    {
        $param = new self($name);
        $parsedRules = explode('|', $rule);

        foreach ($parsedRules as $concreteRule) {            
            try {
                $value = explode(':', $concreteRule)[1];
            } catch (Exception $e) {
                $value = null;
            }
            
            try {
                $param->{$concreteRule}($value);
            } catch (Error $e) {
                continue;
            } catch (BadMethodCallException $e) {
                continue;
            }
        }
        
        return $param;
    }

    /**
     * Serialization of object.
     *
     * @return array
     */
    public function toArray(): array
    {
        $serialized = [
            'name' => $this->name,
            'required' => $this->required,
            'example' => $this->example,
            'description' => $this->description,
            'in' => $this->in,
            'schema' => [
                'type' => $this->type,
                'nullable' => $this->nullable,
            ],
        ];

        if ($this->items) {
            $serialized['items'] = $this->items;
        }

        if ($this->max) {
            $serialized['schema']['maximum'] = $this->max;
        }

        if ($this->min) {
            $serialized['schema']['minimum'] = $this->min;
        }

        return $serialized;
    }

    public function setType(string $type)
    {
        $this->type = $type;

        if ($type === 'array') {
            $this->items = [
                'type' => 'string',
            ];
        }

        return $this;
    }

    public function required($value): self
    {
        $this->required = true;
        return $this;
    }

    public function nullable($value): self
    {
        $this->nullable = true;
        return $this;
    }

    public function max($value)
    {
        $this->max = $value;
        return $this;
    }

    public function min($value)
    {
        $this->min($value);
        return $this;
    }
}
