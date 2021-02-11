<?php

namespace LaravelRoad\JetGen\Tests\Unit;

use Illuminate\Filesystem\Filesystem;
use LaravelRoad\JetGen\Commands\MigrationCommand;
use LaravelRoad\JetGen\Tests\TestCase;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * @group migrations
 */
class MigrationCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $file = new Filesystem;
        $file->cleanDirectory(database_path("migrations"));
    }

    /**
     * @group run
     * @test
     */
    public function checkRunMigrationCommand()
    {
        $this
            ->artisan('jetgen:migration', [
                'name' => 'post',
                '--schema' => 'title:string(150), description:text:nullable, user_id:unsignedBigInteger:foreign'
            ])
            ->expectsOutput('Created Migration: CreatePostsTable')
            ->assertExitCode(0);
    }

    /**
     * @group content
     * @test
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function checkCompiledMigration()
    {
        /** @var MigrationCommand $migrationCommnad */
        $migrationCommand = $this->app->make(MigrationCommand::class);

        $parameters = [
            'jetgen:migration',
            'name' => 'post',
            '--schema' => 'title:string(150), description:text:nullable, user_id:foreignId:constrained'
        ];

        $input = new ArrayInput($parameters, $migrationCommand->getDefinition());
        $migrationCommand->setInput($input);
        $migrationCommand->setNames('post');

        $this->assertEquals(
            file_get_contents(__DIR__ . '/../../storage/tests/migration_posts_table.compiled'),
            $migrationCommand->compileStub()
        );
    }
}
