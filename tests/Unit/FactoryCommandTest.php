<?php

namespace LaravelRoad\JetGen\Tests\Unit;

use Illuminate\Filesystem\Filesystem;
use LaravelRoad\JetGen\Commands\FactoryCommand;
use LaravelRoad\JetGen\Tests\TestCase;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * @group factorys
 */
class FactoryCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $file = new Filesystem;
        $file->cleanDirectory(database_path("factories"));
    }

    /**
     * @group run
     * @test
     */
    public function checkRunFactoryCommand()
    {
        $this
            ->artisan('jetgen:factory', [
                'name' => 'post',
                '--schema' => 'title:string(150), description:text:nullable, user_id:unsignedBigInteger:foreign'
            ])
            ->expectsOutput('Created Factory: PostFactory')
            ->assertExitCode(0);
    }

    /**
     * @group content
     * @test
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function checkCompiledFactory()
    {
        /** @var FactoryCommand $factoryCommnad */
        $factoryCommand = $this->app->make(FactoryCommand::class);

        $parameters = [
            'jetgen:factory',
            'name' => 'post',
            '--schema' => 'title:string(150), description:text:nullable, user_id:foreignId:constrained'
        ];

        $input = new ArrayInput($parameters, $factoryCommand->getDefinition());
        $factoryCommand->setInput($input);
        $factoryCommand->setNames('post');

        $this->assertEquals(
            file_get_contents(__DIR__ . '/../../storage/tests/factory_posts.compiled'),
            $factoryCommand->compileStub()
        );
    }
}
