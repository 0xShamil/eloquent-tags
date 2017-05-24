<?php 

use Shamil\Tags\Providers\TagsServiceProvider;

abstract class TestCase extends Orchestra\Testbench\BrowserKit\TestCase
{
    protected function getPackageProviders($app)
    {
        return [TagsServiceProvider::class];
    }

    public function setUp()
    {
        parent::setUp();

        Eloquent::unguard();

        // $this->artisan('migrate', [
        //     '--database' => 'testbench',
        //     '--path' => '../migrations',
        // ]);

        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--path' => '../migrations',
        ]);
    }

    public function tearDown()
    {
        \Schema::drop('lessons');
    }

    protected function getEnvironmentSetup($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        \Schema::create('lessons', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });
    }
}
