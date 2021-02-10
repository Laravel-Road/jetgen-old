<?php

namespace LaravelRoad\JetGen\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use LaravelRoad\JetGen\Contracts\Replaceable;
use LaravelRoad\JetGen\Parsers\SchemaParser;
use LaravelRoad\JetGen\SyntaxBuilders\MigrationSyntaxBuilder;

class MigrationCommand extends AbstractCommand implements Replaceable
{
    /**
     * @var string
     */
    protected $signature = 'jetgen:migration
        {name : Class (singular) for example User}
        {--s|schema= : Schema options?}';

    /**
     * @var string
     */
    protected $description = 'Create a new migration class and apply schema at the same time';

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

        if ($this->files->exists($path = $this->getPath())) {
            $this->error('Migration already exists!');
            return;
        }

        if ($this->migrationAlreadyExist()) {
            $this->error("A {$this->className} class already exists.");
            return;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->compileStub());

        $this->line("<info>Created Migration:</info> {$this->className}");

        $this->composer->dumpAutoloads();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $filename = date('Y_m_d_His') . '_'. Str::snake($this->className);
        return database_path("migration/{$filename}.php");
    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    public function compileStub(): string
    {
        $stub = $this->files->get($this->resolveStubPath('/database/migrations/migration.php.stub'));

        $this
            ->replaceClassName($stub)
            ->replaceSchema($stub)
            ->replaceTableName($stub);

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
    protected function replaceClassName(string &$stub): Replaceable
    {
        $stub = str_replace('{{migrationClassName}}', $this->className, $stub);

        return $this;
    }

    /**
     * @param string $stub
     * @return $this
     */
    protected function replaceSchema(string &$stub): MigrationCommand
    {
        if ($schema = $this->option('schema')) {
            $schema = (new SchemaParser())->parse($schema);
        }

        $schema = (new MigrationSyntaxBuilder())->create($schema);

        $stub = str_replace(['{{schema_up}}', '{{schema_down}}'], $schema, $stub);

        return $this;
    }

    /**
     * @return bool
     * @throws FileNotFoundException
     */
    protected function migrationAlreadyExist(): bool
    {
        $migrationFiles = $this->files->glob(base_path().'/database/migrations/*.php');

        foreach ($migrationFiles as $migrationFile) {
            $this->files->requireOnce($migrationFile);
        }

        return class_exists($this->className);
    }
}
