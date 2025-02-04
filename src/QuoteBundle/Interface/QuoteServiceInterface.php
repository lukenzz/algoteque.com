<?php

declare(strict_types=1);

namespace App\QuoteBundle\Interface;

interface QuoteServiceInterface
{
    public function loadProviders(string $configJson): void;
    public function generateQuotes(array $requestData): array;
}