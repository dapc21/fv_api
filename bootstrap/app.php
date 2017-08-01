<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Log configuration
|--------------------------------------------------------------------------
|
*/
$app->configureMonologUsing(function($monolog) {
    $format = "[%datetime%] %extra.process_id% %level_name%: %message%\n";
    $allowInlineLineBreaks = true;
    $ignoreEmptyContextAndExtra = true;
    $includeStacktraces = null;
    $dateFormat = "Y-m-d H:i:s";
    $formatter = new \Monolog\Formatter\LineFormatter($format, $dateFormat, $allowInlineLineBreaks , $ignoreEmptyContextAndExtra);



    $requestHandler = new \Monolog\Handler\RotatingFileHandler(storage_path('logs/').'request.log');
    $requestHandler->setFilenameFormat('{filename}-{date}', 'Y-m-d');
    $requestHandler->setFormatter($formatter);
    $requestHandler->pushProcessor(new \Monolog\Processor\ProcessIdProcessor());
    $requestHandler->setLevel(\Monolog\Logger::INFO);
    $filterHandler = new \Monolog\Handler\FilterHandler($requestHandler, \Monolog\Logger::INFO, \Monolog\Logger::INFO);
    $monolog->pushHandler($filterHandler);

    $errorHandler = new \Monolog\Handler\RotatingFileHandler(storage_path('logs/').'error.log');
    $errorHandler->setFilenameFormat('{filename}-{date}', 'Y-m-d');
    $errorHandler->setFormatter($formatter);
    $errorHandler->pushProcessor(new \Monolog\Processor\ProcessIdProcessor());
    $errorHandler->setLevel(\Monolog\Logger::ERROR);
    $monolog->pushHandler($errorHandler);
});


/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
