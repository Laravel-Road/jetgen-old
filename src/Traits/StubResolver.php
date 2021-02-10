<?php

namespace LaravelRoad\JetGen\Traits;

trait StubResolver
{
    /**
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = base_path('stubs/jetgen/'.trim($stub, '/')))
            ? $customPath
            : __DIR__.'/../../stubs'.$stub;
    }
}
