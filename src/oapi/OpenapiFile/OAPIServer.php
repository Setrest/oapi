<?php

namespace Setrest\OAPIDocumentation\OpenapiFile;

use Setrest\OAPIDocumentation\OpenapiFile\Interfaces\OAPISectionInterface;

class OAPIServer implements OAPISectionInterface
{
    protected $servers = [];

    public function __construct(string $url, string $description)
    {
        $this->url = $url;
        $this->description = $description;

        $this->servers[] = (object) [
            'url' => $url,
            'description' => $description
        ]; 
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'description' => $this->description,
        ];
    }

    /**
     * @return array|null
     */
    public static function initFromConfig(): ?array
    {
        $servers = null;

        foreach (config('oapidocs.servers') as $server) {
            $servers[] = (new self($server['url'], $server['description']))->toArray();
        }

        return $servers;
    }
}