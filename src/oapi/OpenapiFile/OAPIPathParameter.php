<?php

namespace  Setrest\OAPIDocumentation\OpenapiFile;

use BadMethodCallException;
use Error;
use Exception;

class OAPIPathParameter
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $in;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $example;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var array
     */
    protected $items;

    /**
     * @var bool
     */
    protected $nullable;

    /**
     * @var mixed
     */
    protected $max;

    /**
     * @var mixed
     */
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

    /**
     * Detecting laravel validation params
     */
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

    /**
     * Analysis of the found query rules
     *
     * @param string $name
     * @param mixed $rule
     * @return self
     */
    public static function parseFromValidation(string $name, $rule): self
    {
        $param = new self($name);
        $rule = !is_string($rule) ? $rule->__toString() : $rule; 
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
     * Converting to array from data of object.
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

    /**
     * @return self
     */
    public function required($value): self
    {
        $this->required = true;
        return $this;
    }

    /**
     * @return self
     */
    public function nullable($value): self
    {
        $this->nullable = true;
        return $this;
    }

    /**
     * @return self
     */
    public function max($value): self
    {
        $this->max = $value;
        return $this;
    }

    /**
     * @return self
     */
    public function min($value): self
    {
        $this->min($value);
        return $this;
    }
}
