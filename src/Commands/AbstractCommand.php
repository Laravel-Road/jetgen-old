<?php

namespace LaravelRoad\JetGen\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use LaravelRoad\JetGen\Traits\ReplaceConventions;
use LaravelRoad\JetGen\Traits\StubResolver;

abstract class AbstractCommand extends Command
{
    use ReplaceConventions;
    use StubResolver;

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @return string
     */
    abstract public function getPath(): string;

    /**
     * @return string
     */
    abstract public function compileStub(): string;

    /**
     * @param Filesystem $files
     * @param Composer   $composer
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->setNames($this->argument('name'));
    }

    /**
     * @param string $path
     */
    protected function makeDirectory(string $path): void
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true, true);
        }
    }
}
