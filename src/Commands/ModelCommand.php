<?php

namespace LaravelRoad\JetGen\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use LaravelRoad\JetGen\Contracts\Replaceable;
use LaravelRoad\JetGen\Parsers\SchemaParser;
use LaravelRoad\JetGen\SyntaxBuilders\FactorySyntaxBuilder;
use LaravelRoad\JetGen\SyntaxBuilders\ModelSyntaxBuilder;

class ModelCommand extends AbstractCommand implements Replaceable
{
    /**
     * @var string
     */
    protected $signature = 'jetgen:model
        {name : Model name (singular) for example User}
        {--s|schema= : Schema options?}';

    /**
     * @var string
     */
    protected $description = 'Create a new model class and apply schema at the same time';


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

        if ($this->files->exists($path = $this->getPath())) {
            $this->error('Factory already exists!');
            return;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->compileStub());

        $this->line("<info>Created Model:</info> {$this->model}");

        $this->composer->dumpAutoloads();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return is_dir(app_path('Models'))
            ? app_path("Models/{$this->model}.php")
            : app_path("{$this->model}.php");
    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    public function compileStub(): string
    {
        $stub = $this->files->get($this->resolveStubPath('/app/Models/Model.php.stub'));

        $this
            ->replaceSchema($stub)
            ->replaceModelNamespace($stub)
            ->replaceModelName($stub);

        return $stub;
    }

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceSchema(string &$stub): ModelCommand
    {
        if ($schema = $this->option('schema')) {
            $schema = (new SchemaParser())->parse($schema);
        }

        $schema = (new ModelSyntaxBuilder())->create($schema);

        $stub = str_replace(['{{column}}', '{{foreign}}'], $schema, $stub);
//        $stub = str_replace("\n\n", "\n", $stub);
        $stub = str_replace(str_repeat(' ', 4) . "\n", "\n", $stub);

        return $this;
    }
}
