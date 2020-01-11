<?php

namespace Tests;

use Dotenv\Dotenv;
use Dotenv\Environment\DotenvFactory;
use Dotenv\Loader;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

//        if (file_exists(dirname(__DIR__) . '/.env.test')) {
//            $dotenv = \Dotenv\Dotenv::create(dirname(__DIR__), '.env.test');
//            $dotenv->load();
//        }

        return $app;
    }
}
