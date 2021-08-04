<?php

namespace  Setrest\OAPIDocumentation\OpenapiFile;

use Setrest\OAPIDocumentation\OpenapiFile\Interfaces\OAPISectionInterface;

class OAPIInfo implements OAPISectionInterface
{
    /**
     * Title of documentation
     *
     * @var string
     */
    protected $title = null;

    /**
     * Description of documentation
     *
     * @var string
     */
    protected $description = null;

    /**
     * Version of documentation
     *
     * @var string
     */
    protected $version = null;

    public function __construct(string $title, string $description = "", string $version = "1.0.0")
    {
        $this->title = $title;
        $this->description = $description ?? "";
        $this->version = $version ?? "1.0.0";
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'version' => $this->version,
        ];
    }

    /**
     * @return self
     */
    public static function initFromConfig(): self
    {
        $info = new self(config('oapidocs.title', 'Default'), config('oapidocs.description', "Description"), config('oapidocs.version', "1.0.0"));
        return $info;
    }
}
