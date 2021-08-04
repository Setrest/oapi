<?php

namespace Setrest\OAPIDocumentation\Router\ResponseFinders\Interfaces;

use Setrest\OAPIDocumentation\Router\ResponseSpec;

interface FinderInterface
{
    public function setNext(FinderInterface $finder): FinderInterface;

    public function find(array $returnCode, array $methodCode): ?ResponseSpec;
}