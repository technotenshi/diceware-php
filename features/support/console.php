<?php

// Run the real command/kernel against disposable resources and a mocked HTTP transport.
$_ENV['DB_CONNECTION'] = $_SERVER['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $_SERVER['DB_DATABASE'] = $argv[1].'/app/database.sqlite';
$_ENV['APP_ENV'] = $_SERVER['APP_ENV'] = 'testing';
$app = require dirname(__DIR__, 2).'/bootstrap/app.php';
$app->useStoragePath($argv[1]);
$app['config']->set('filesystems.disks.local.root', $argv[1].'/app');
$app->instance(GuzzleHttp\Client::class, new GuzzleHttp\Client([
    'handler' => GuzzleHttp\HandlerStack::create(new GuzzleHttp\Handler\MockHandler([
        new GuzzleHttp\Psr7\Response(200, [], "11111\tabacus\n"),
    ])),
]));
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
exit($kernel->handle(
    new Symfony\Component\Console\Input\StringInput($argv[2]),
    new Symfony\Component\Console\Output\ConsoleOutput()
));
