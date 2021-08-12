<?php

namespace Setrest\OAPIDocumentation;

use Setrest\OAPIDocumentation\OpenapiFile\OAPIInfo;
use Setrest\OAPIDocumentation\OpenapiFile\OAPIPath;
use Setrest\OAPIDocumentation\OpenapiFile\OAPIServer;

class OpenapiFile
{
    /**
     * @var resource
     */
    protected $file;

    /**
     * @var string
     */
    protected $openApi = '3.0.0';

    /**
     * @var OAPIInfo|null
     */
    protected $apiInfo = null;

    /**
     * @var OAPIServer|null
     */
    protected $apiServers = null;

    /**
     * @var array
     */
    protected $apiPaths = null;

    public function __construct(string $path, string $fileName = 'openapi.json')
    {
        $this->file = fopen($path . '/' . $fileName, 'w');
    }

    /**
     * @param string $title
     * @param string $descriprtion
     * @param string $version
     * @return void
     */
    public function addInfo(string $title, string $descriprtion = "", string $version = "1.0")
    {
        $this->apiInfo = (new OAPIInfo($title, $descriprtion, $version))->toArray();
        return $this;
    }

    /**
     * @param string $url
     * @param string $description
     * @return self
     */
    public function addServer(string $url, string $description = ""): self
    {
        $this->apiServers[] = (new OAPIServer($url, $description))->toArray();
        return $this;
    }

    /**
     * @param OAPIPath $path
     * @return self
     */
    public function addPath(OAPIPath $path): self
    {
        $this->apiPaths[$path->getPath()][$path->getMethod()] = $path->toArray();
        return $this;
    }

    /**
     * Saving the documentation to an openapi json file.
     *
     * @return self
     */
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
