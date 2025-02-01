<?php

declare(strict_types=1);

namespace Recruitment\Service;

use Recruitment\Model\DtoProvider;
use Recruitment\Model\TopicRequest;

class QuoteService
{
    private array $providers = [];

    public function loadProviders(string $configJson): void
    {
        $config = json_decode($configJson, true);
        
        foreach ($config['provider_topics'] as $providerName => $topicsItem) {
            $topics = explode('+', $topicsItem);
            $this->providers[] = new DtoProvider($providerName, $topics);
        }
    }

    public function generateQuotes(QuoteCalculator $quoteCalculator, array $requestData): array
    {
        $request = new TopicRequest($requestData['topics']);
        $quotes = [];

        foreach ($this->providers as $provider) {
            $quote = $quoteCalculator->calculateQuote($provider, $request);
            $quotes[$provider->getName()] = $quote;
        }

        return $quotes;
    }
}