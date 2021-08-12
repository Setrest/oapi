<?php

namespace Setrest\OAPIDocumentation;

use Setrest\OAPIDocumentation\OpenapiFile\OAPIPath;
use Illuminate\Support\Facades\Route;

class Documentation
{
    /**
     * Contains the path to the directory where the documentation is stored.
     *
     * @var string
     */
    protected $dirPath;

    /**
     * Added path of folder for storing documentation.
     *
     * @param string $path
     * @return self
     */
    public function addDirPath(string $path = null): self
    {
        $this->dirPath = $path ? storage_path($path) : storage_path("openapi");

        if (!is_dir($this->dirPath)) {
            mkdir($this->dirPath, 0755, true);
        }

        return $this;
    }

    /**
     * Getting directory path.
     *
     * @return string
     */
    public function getDirPath(): string
    {
        if (!$this->dirPath) {
            $this->addDirPath();
        }

        return $this->dirPath;
    }

    /**
     * Generating documentation staticaly.
     *
     * @return OpenapiFile
     */
    public static function staticGenerate(): OpenapiFile
    {
        $self = new self;
        $rs = new RouterService(Route::getFacadeRoot());
        $routes = $rs->getApiRoutes();
        $info = $rs->parseRoutes($routes);

        $openapiFile = $self->createOpenapiFile();

        foreach ($info as $paths) {
            foreach ($paths as $path) {
                foreach ($path->getMethods() as $method) {
                    $pathObject = new OAPIPath($path->getUri(), $path->getSummary() ?? $path->getUri(), $method, $path->getTags() ?? null);

                    if (!empty($rules = $path->getRules())) {
                        foreach ($rules as $field => $rule) {
                            $pathObject->addParameterFromValidation($field, $rule);
                        }
                    }

                    $pathObject->addResponseFromSpecs($path->getResponse());

                    $openapiFile->addPath($pathObject);
                }
            }
        }

        $openapiFile->push();
        return $openapiFile;
    }

    /**
     * Generating documentation.
     *
     * @return OpenapiFile
     */
    public function generate(): OpenapiFile
    {
        $rs = new RouterService(Route::getFacadeRoot());
        $routes = $rs->getApiRoutes();
        $info = $rs->parseRoutes($routes);

        $openapiFile = $this->createOpenapiFile();

        foreach ($info as $paths) {
            foreach ($paths as $path) {
                foreach ($path->getMethods() as $method) {
                    $pathObject = new OAPIPath($path->getUri(), $path->getSummary() ?? $path->getUri(), $method, $path->getTags() ?? null);

                    if (!empty($rules = $path->getRules())) {
                        foreach ($rules as $field => $rule) {
                            $pathObject->addParameterFromValidation($field, $rule);
                        }
                    }

                    $pathObject->addResponseFromSpecs($path->getResponse());

                    $openapiFile->addPath($pathObject);
                }
            }
        }

        $openapiFile->push();
        return $openapiFile;
    }

    /**
     * Makes a new object of configuration of OpenAPI file.
     *
     * @return OpenapiFile
     */
    public function createOpenapiFile(): OpenapiFile
    {
        $file = new OpenapiFile($this->getDirPath(), 'openapi.json');
        return $file;
    }
}