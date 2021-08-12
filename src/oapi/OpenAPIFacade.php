<?php

namespace Setrest\OAPIDocumentation;

use Illuminate\Support\Facades\Facade;

class OpenAPIFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Documentation::class;
    }
}
