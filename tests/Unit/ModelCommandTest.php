<?php

namespace LaravelRoad\JetGen\Tests\Unit;

use Illuminate\Filesystem\Filesystem;
use LaravelRoad\JetGen\Commands\ModelCommand;
use LaravelRoad\JetGen\Tests\TestCase;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * @group models
 */
class ModelCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $file = new Filesystem;
        $file->delete(app_path("Models/Post.php"));
    }

    /**
     * @group run
     * @test
     */
    public function checkRunModelCommand()
    {
        $this
            ->artisan('jetgen:model', [
                'name' => 'post',
                '--schema' => 'title:string(150), description:text:nullable, user_id:unsignedBigInteger:foreign'
            ])
            ->expectsOutput('Created Model: Post')
            ->assertExitCode(0);
    }

    /**
     * @group content
     * @test
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function checkCompiledModel()
    {
        /** @var ModelCommand $modelCommnad */
        $modelCommand = $this->app->make(ModelCommand::class);

        $parameters = [
            'jetgen:model',
            'name' => 'post',
            '--schema' => 'title:string(150), description:text:nullable, user_id:foreignId:constrained'
        ];

        $input = new ArrayInput($parameters, $modelCommand->getDefinition());
        $modelCommand->setInput($input);
        $modelCommand->setNames('post');

        $this->assertEquals(
            file_get_contents(__DIR__ . '/../../storage/tests/model_posts.compiled'),
            $modelCommand->compileStub()
        );
    }
}
