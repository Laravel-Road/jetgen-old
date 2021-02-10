<?php

namespace LaravelRoad\JetGen\SyntaxBuilders;

use LaravelRoad\JetGen\Traits\StubResolver;

class AbstractSintaxBuilder
{
    use StubResolver;

    /**
     * @var string
     */
    protected string $template;

    /**
     * @var array
     */
    protected array $bluePrintTypes;

    public function __construct()
    {
        $this->bluePrintTypes = config('jetgen.blueprint_types');
    }
}
