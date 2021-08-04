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

    public function setNext(FinderInterface $finder): FinderInterface
    {
        $this->nextFinder = $finder;
        return $finder;
    }

    public function find(array $returnCode, array $methodCode): ?ResponseSpec
    {
        if ($this->nextFinder) {
            return $this->nextFinder->find($returnCode, $methodCode);
        }

        return null;
    }

    protected function getReturnCode(): string
    {
        return $this->returnCode;
    }

    protected function getMethodCode(): string
    {
        return $this->methodCode;
    }
}