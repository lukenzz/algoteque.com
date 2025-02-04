<?php

declare(strict_types=1);

namespace App\QuoteBundle\Service;

use App\QuoteBundle\Exception\InvalidConfigurationException;
use App\QuoteBundle\Exception\InvalidRequestException;
use App\QuoteBundle\Interface\QuoteServiceInterface;
use App\QuoteBundle\Interface\QuoteCalculatorInterface;
use App\QuoteBundle\Model\DtoProvider;
use App\QuoteBundle\Model\TopicRequest;
use App\Service\LoggerService;

class QuoteService implements QuoteServiceInterface
{
    private array $providers = [];

    public function __construct(
        private readonly QuoteCalculatorInterface $calculator,
        private readonly LoggerService $logger
    ) {
    }

    public function loadProviders(string $configJson): void
    {
        if (!file_exists($configJson) || !json_validate(file_get_contents($configJson))) {
            throw new InvalidConfigurationException('Data is not a valid JSON or provider file not exist.');
        }
        $config = json_decode(file_get_contents($configJson), true);

        if (!isset($config['provider_topics']) || !is_array($config['provider_topics'])) {
            throw new InvalidConfigurationException('Invalid provider configuration format');
        }

        $this->providers = [];
        foreach ($config['provider_topics'] as $providerName => $topicsString) {
            $topics = explode('|', $topicsString);
            $this->providers[] = new DtoProvider($providerName, $topics);
        }

        $this->logger->logMessage(
            sprintf(
                'Providers generated successfully: count %s',
                count($this->providers)
            )
        );
    }

    public function generateQuotes(array $requestData): array
    {
        if (!isset($requestData['topics'])) {
            throw new InvalidRequestException('Topics field is required');
        }

        if (!is_array($requestData['topics'])) {
            throw new InvalidRequestException('Topics must be an array');
        }

        if (empty($requestData['topics'])) {
            throw new InvalidRequestException('Topics cannot be empty');
        }

        foreach ($requestData['topics'] as $topic => $count) {
            if (!is_string($topic)) {
                throw new InvalidRequestException('Topic name must be a string');
            }
            if (!is_int($count) || $count < 0) {
                throw new InvalidRequestException('Topic count must be a positive integer');
            }
        }

        $request = new TopicRequest($requestData['topics']);
        $quotes = [];

        foreach ($this->providers as $provider) {
            $quote = $this->calculator->calculateQuote($provider, $request);
            if ($quote > 0) {
                $quotes[$provider->getName()] = $quote;
            }
        }

        $this->logger->logMessage(
            sprintf(
                'Quotes generated successfully: count %s',
                count($quotes)
            )
        );

        return $quotes;
    }
}