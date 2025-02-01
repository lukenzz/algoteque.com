<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Recruitment\Service\QuoteService;
use Recruitment\Service\QuoteCalculator;

header('Content-Type: application/json');

$development = false;

try {
    if ($development) {
        $requestData = '{
    "topics": {
        "reading": 20,
        "math": 50,
        "science": 30,
        "history": 15,
        "art": 10
        }
    }';
        $requestData = json_decode($requestData, true);
    } else {
        $input = file_get_contents('php://input');
        if (!json_validate($input)) {
            throw new InvalidArgumentException('Invalid JSON request');
        }
        $requestData = json_decode($input, true);
    }

    $service = new QuoteService();

    $providerConfig = json_encode([
        'provider_topics' => [
            'provider_a' => 'math+science',
            'provider_b' => 'reading+science',
            'provider_c' => 'history+math'
        ]
    ], JSON_THROW_ON_ERROR);

    $service->loadProviders($providerConfig);

    $quotes = $service->generateQuotes(new QuoteCalculator(), $requestData);
    
    echo json_encode([
        'status' => 'success',
        'quotes' => $quotes
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}