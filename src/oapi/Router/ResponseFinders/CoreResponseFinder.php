<?php

namespace Setrest\OAPIDocumentation\Router\ResponseFinders;

use Setrest\OAPIDocumentation\Router\ResponseFinders\Interfaces\FinderInterface;
use Setrest\OAPIDocumentation\Router\ResponseSpec;

abstract class CoreResponseFinder implements FinderInterface
{
    /**
     * @var FinderInterface
     */
    private $nextFinder;

    private $returnCode = null;

    private $methodCode = null;

    public function setNext(FinderInterface $finder): FinderInterface
    {
        $this->nextFinder = $finder;
        return $finder;
    }

    /**
     * Finds specific response elements
     *
     * @param array $returnCode
     * @param array $methodCode
     * @return ResponseSpec|null
     */
    public function find(array $returnCode, array $methodCode): ?ResponseSpec
    {
        if ($this->nextFinder) {
            return $this->nextFinder->find($returnCode, $methodCode);
        }

        return null;
    }

    /**
     * Skiping if not found needed part of code
     *
     * @return void
     */
    protected function skip(array $returnCode, array $methodCode)
    {
        return self::find($returnCode, $methodCode);
    }

    /**
     * Getting a return code
     *
     * @return array
     */
    protected function getReturnCode(): array
    {
        return $this->returnCode;
    }

    /**
     * Getting a full code of concrete method
     *
     * @return array
     */
    protected function getMethodCode(): array
    {
        return $this->methodCode;
    }
}