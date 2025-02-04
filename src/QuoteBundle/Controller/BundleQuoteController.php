<?php

declare(strict_types=1);

namespace App\QuoteBundle\Controller;

use App\QuoteBundle\Exception\InvalidRequestException;
use App\QuoteBundle\Interface\QuoteServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BundleQuoteController
{
    public function __construct(
        private readonly QuoteServiceInterface $quoteService
    ) {}

    public function generateQuotes(Request $request): JsonResponse
    {
        $data = $request->getContent();
        if ($request->getContent() !== 'POST' &&  $_ENV['APP_ENV'] !== 'dev') {
            throw new InvalidRequestException('Method not allowed');
        }
        if (!json_validate($data) &&  $_ENV['APP_ENV'] !== 'dev') {
            throw new InvalidRequestException('Data is not a valid JSON');
        }
        if ($_ENV['APP_ENV'] === 'dev') {
            $data = '{
                "topics": {
                    "reading": 20,
                    "math": 50,
                    "science": 30,
                    "history": 15,
                    "art": 10
                    }
                }';
        }
        $this->quoteService->loadProviders(__DIR__ . '/../../../config/' . $_ENV['PROVIDER_CONFIG_PATH']);
        $quotes = $this->quoteService->generateQuotes(
            json_decode($data, true)
        );
        
        return new JsonResponse([
            'status' => 'success',
            'quotes' => $quotes
        ]);
    }
}