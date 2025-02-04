<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

error_reporting(E_ALL);
ini_set('display_errors', $_ENV['APP_DEBUG']);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

$container = new ContainerBuilder();
$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../config/'));
$loader->load('services.yaml');
$container->compile();

$logger = $container->get(App\Service\LoggerService::class);
$request = Request::createFromGlobals();

$logger->logMessage(
    sprintf('Start generate quotes')
);

try {
    $controller = $container->get(App\QuoteBundle\Controller\BundleQuoteController::class);
    $response = $controller->generateQuotes($request);
} catch (\Throwable $e) {
    $logger->logMessage(
        sprintf(
            'Error processing request: %s, trace: %s',
            $e->getMessage(),
            $e->getTraceAsString()
        ),
        'error'
    );

    $response = new JsonResponse([
        'status' => 'error',
        'message' => $e->getMessage()
    ], 400);
}

$response->send();