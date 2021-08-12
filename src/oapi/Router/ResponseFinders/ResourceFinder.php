<?php

namespace Setrest\OAPIDocumentation\Router\ResponseFinders;

use Setrest\OAPIDocumentation\Router\ResponseSpec;

class ResourceFinder extends CoreResponseFinder
{
    public function find(array $returnCode, array $methodCode): ?ResponseSpec
    {
        $responseSpec = new ResponseSpec();
        return parent::find($returnCode, $methodCode);

        return null;
    }
}