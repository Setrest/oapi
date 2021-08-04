<?php

namespace  Setrest\OAPIDocumentation\OpenapiFile\Interfaces;

interface OAPISectionInterface
{
    /**
     * Getting a formatted array of an oAPI section
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Init value from config file
     *
     * @return self
     */
    public static function initFromConfig();
}