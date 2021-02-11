<?php

namespace LaravelRoad\JetGen\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use LaravelRoad\JetGen\Contracts\Replaceable;
use LaravelRoad\JetGen\Parsers\SchemaParser;
use LaravelRoad\JetGen\SyntaxBuilders\FactorySyntaxBuilder;

class FactoryCommand extends AbstractCommand implements Replaceable
{
    /**
     * @var string
     */
    protected $signature = 'jetgen:factory
        {name : Model name (singular) for example User}
        {--s|schema= : Schema options?}';

    /**
     * @var string
     */
    protected $description = 'Create a new factory class and apply schema at the same time';

    /**
     * @var string
     */
    protected string $className;

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     *
     * @return void
     */
    public function handle(): void
    {
        parent::handle();
        $this->className = $this->model . 'Factory';

        if ($this->files->exists($path = $this->getPath())) {
            $this->error('Factory already exists!');
            return;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->compileStub());

        $this->line("<info>Created Factory:</info> {$this->className}");

        $this->composer->dumpAutoloads();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return database_path("factories/{$this->className}.php");
    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    public function compileStub(): string
    {
        $stub = $this->files->get($this->resolveStubPath('/database/factories/factory.php.stub'));

        $this
            ->replaceSchema($stub)
            ->replaceNamespace($stub)
            ->replaceModelNamespace($stub)
            ->replaceModelName($stub);

        return $stub;
    }

    /**
     * @param string $name
     */
    public function setNames(string $name): void
    {
        parent::setNames($name);

        $this->className = 'Create' . Str::studly($this->table) . 'Table';
    }

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceSchema(string &$stub): FactoryCommand
    {
        if ($schema = $this->option('schema')) {
            $schema = (new SchemaParser())->parse($schema);
        }

        $stub = (new FactorySyntaxBuilder())->create($schema);

        return $this;
    }
}
