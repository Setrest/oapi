<?php

namespace Setrest\OAPIDocumentation\Router;

class RouteSpec
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $methods;

    /**
     * @var boolean
     */
    private $isQuest = false;

    /**
     * @var array|null
     */
    private $tags;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var array
     */
    private $rules;

    /**
     * @var ResponseSpec
     */
    private $response;

    public function __construct(string $uri, array $methods, bool $isQuest = false)
    {
        $this->uri = $uri;
        $this->methods = $methods;
        $this->isQuest = $isQuest;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function isQuest(): bool
    {
        return $this->isQuest;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function getResponse(): ResponseSpec
    {
        return $this->response;
    }

    public function addSummary(string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    public function addTag(string $tag): self
    {
        $this->tags[] = $tag;
        return $this;
    }

    public function addTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    public function addRules(?array $rules): self
    {
        $this->rules = $rules ?? [];
        return $this;
    }

    public function addResponse(ResponseSpec $response): self
    {
        $this->response = $response;
        return $this;
    }
}
