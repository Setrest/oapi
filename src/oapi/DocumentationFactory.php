<?php

namespace Setrest\OAPIDocumentation;

class DocumentationFactory
{
    /**
     * @var ConfigFactory
     */
    private $configFactory;

    public function __construct(ConfigFactory $configFactory)
    {
        $this->configFactory = $configFactory;
    }

    /**
     * Make Generator Instance.
     *
     * @param string $documentation
     * @return Documentation
     * @throws L5SwaggerException
     */
    public function make(string $documentation = null): Documentation
    {
        $config = $this->configFactory->documentationConfig($documentation);

        $documentationGenrator = new Documentation;
        $documentationGenrator->addDirPath($config['storage_path'] ?? null);

        
        return $documentationGenrator;
    }
}
