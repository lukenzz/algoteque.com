<?php

declare(strict_types=1);

namespace Recruitment\Tests\Service;

use Recruitment\Service\QuoteService;
use Recruitment\Service\QuoteCalculator;
use PHPUnit\Framework\TestCase;

class QuoteServiceTest extends TestCase
{
    private QuoteService $service;

    protected function setUp(): void
    {
        $this->service = new QuoteService();
        
        $config = json_encode([
            'provider_topics' => [
                'provider_a' => 'math+science',
                'provider_b' => 'reading+science',
                'provider_c' => 'history+math'
            ]
        ]);
        
        $this->service->loadProviders($config);
    }

    public function testGenerateQuotes(): void
    {
        $request = [
            'topics' => [
                'reading' => 20,
                'math' => 50,
                'science' => 30,
                'history' => 15,
                'art' => 10
            ]
        ];

        $quotes = $this->service->generateQuotes(new QuoteCalculator(), $request);

        $this->assertEqualsWithDelta(8.0, $quotes['provider_a'], 0.01);
        $this->assertEqualsWithDelta(5.0, $quotes['provider_b'], 0.01);
        $this->assertEqualsWithDelta(10, $quotes['provider_c'], 0.01);
    }
}