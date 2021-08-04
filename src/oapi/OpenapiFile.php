<?php

namespace Setrest\OAPIDocumentation;

use Setrest\OAPIDocumentation\OpenapiFile\OAPIInfo;
use Setrest\OAPIDocumentation\OpenapiFile\OAPIPath;
use Setrest\OAPIDocumentation\OpenapiFile\OAPIServer;

class OpenapiFile
{
    protected $file;

    protected $openApi = '3.0.0';

    protected $apiInfo = null;

    protected $apiServers = null;

    protected $apiPaths = null;

    public function __construct(string $path, string $fileName = 'openapi.json')
    {
        $this->file = fopen($path . '/' . $fileName, 'w');
    }

    public function addInfo(string $title, string $descriprtion = "", string $version = "1.0")
    {
        $this->apiInfo = (new OAPIInfo($title, $descriprtion, $version))->toArray();
        return $this;
    }

    public function addServer(string $url, string $description = ""): self
    {
        $this->apiServers[] = (new OAPIServer($url, $description))->toArray();
        return $this;
    }

    public function addPath(OAPIPath $path): self
    {
        $this->apiPaths[$path->getPath()][$path->getMethod()] = $path->toArray();
        return $this;
    }

    public function push(): self
    {
        $writable = [
            'openapi' => $this->openApi,
        ];

        $writable['info'] = $this->apiInfo ?? OAPIInfo::initFromConfig()->toArray();
        $writable['servers'] = $this->apiServers ?? OAPIServer::initFromConfig();
        $writable['paths'] = $this->apiPaths ?? null;
        $writable['responses'] = 

        fwrite($this->file, json_encode($writable, JSON_PRETTY_PRINT));
        fclose($this->file);

        return $this;
    }
}
