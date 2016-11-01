<?php
require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

//$isDebug = getenv('DEBUG') == 1;
$isDebug = true;

$file = __DIR__ .'/../var/cache/container.php';
$containerConfigCache = new \Symfony\Component\Config\ConfigCache($file, $isDebug);
if($isDebug) {
    define('C3_CODECOVERAGE_ERROR_LOG_FILE', __DIR__.'/../var/logs/c3_error.log'); //Optional (if not set the default c3 output dir will be used)
    include __DIR__.'/../c3.php';
}

if (!$containerConfigCache->isFresh()) {
    //container initialization
    $containerBuilder = new ContainerBuilder();
    $containerBuilder->setParameter('kernel.root_dir',__DIR__);
    $containerBuilder->setParameter('kernel.resources_dir',__DIR__.'/../resources');
    $containerBuilder->setParameter('kernel.cache_dir',__DIR__.'/../var/cache');
    $containerBuilder->setParameter('kernel.logs_dir',__DIR__.'/../var/logs');
    $containerBuilder->setParameter('kernel.isDebug',$isDebug);
    $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__.'/../resources'));
    $loader->load('services.yml');

    $containerBuilder->compile();

    $dumper = new \Symfony\Component\DependencyInjection\Dumper\PhpDumper($containerBuilder);
    $containerConfigCache->write(
        $dumper->dump(array('class' => 'AppContainer')),
        $containerBuilder->getResources()
    );
}

require_once $file;

$container = new AppContainer();

//Create the request instance
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

/** @var $kernel \Symfony\Component\HttpKernel\HttpKernel */
//get the kernel instance
$kernel = $container->get("http-kernel");

// actually execute the kernel, which turns the request into a response
// by dispatching events, calling a controller, and returning the response
$response = $kernel->handle($request);

// send the headers and echo the content
$response->send();

// triggers the kernel.terminate event
$kernel->terminate($request, $response);