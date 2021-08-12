<?php

namespace Setrest\OAPIDocumentation\OpenapiFile;

use Setrest\OAPIDocumentation\OpenapiFile\Interfaces\OAPISectionInterface;

class OAPIServer implements OAPISectionInterface
{
    /**
     * @var array
     */
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
     * Converting to array from data of object.
     * Returns array with:
     * - url
     * - description
     * 
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
     * Add servers from oapi config
     * 
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