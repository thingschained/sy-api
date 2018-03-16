<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades();

$app->withEloquent();

$app->configure('define');
/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//    App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.

|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../routes/web.php';
	require __DIR__.'/../app/Lib/functions.php'; 
    require __DIR__.'/../app/Lib/curl.php'; 
});
 
/*
|--------------------------------------------------------------------------
| Logging app behavior
| time  trace_origin %level_name% request_id 业务说明 结果 上下文 
|--------------------------------------------------------------------------
*/

$app->configureMonologUsing(function($monolog) 
{
    
    $levels = Monolog\Logger::DEBUG;
    $file = __DIR__.'/../storage/logs/'.date('Ymd').'_api.log';

    $dateFormat = "Y-m-d H:i:s.u";
    $output = "[%datetime%]        %message%    %context%\n";
    $formatter = new Monolog\Formatter\LineFormatter($output, $dateFormat);

    $StreamHandler = new Monolog\Handler\StreamHandler($file, $levels);
    $StreamHandler->setFormatter($formatter);
    $monolog->pushHandler($StreamHandler);
    $monolog->pushProcessor(function ($record) {
        return $record;
    });

    return $monolog;
});

return $app;
