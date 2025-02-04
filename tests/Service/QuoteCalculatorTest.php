<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\QuoteBundle\Model\DtoProvider;
use App\QuoteBundle\Model\TopicRequest;
use App\QuoteBundle\Service\QuoteCalculator;
use PHPUnit\Framework\TestCase;

class QuoteCalculatorTest extends TestCase
{
    private QuoteCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new QuoteCalculator();
    }

    /**
     * @dataProvider provideCalculateQuoteData
     */
    public function testCalculateQuote(array $providerTopics, array $requestTopics, float $expectedQuote): void
    {
        $provider = new DtoProvider('test_provider', $providerTopics);
        $request = new TopicRequest($requestTopics);

        $quote = $this->calculator->calculateQuote($provider, $request);
        
        $this->assertEqualsWithDelta($expectedQuote, $quote, 0.01);
    }

    public static function provideCalculateQuoteData(): array
    {
        return [
            'two_matches_high_priority' => [
                ['math', 'science'],
                ['math' => 50, 'science' => 30, 'reading' => 20],
                8.0
            ],
            'single_match_highest_priority' => [
                ['math'],
                ['math' => 50, 'science' => 30, 'reading' => 20],
                10.0
            ],
            'single_match_second_priority' => [
                ['science'],
                ['math' => 50, 'science' => 30, 'reading' => 20],
                7.5
            ],
            'single_match_third_priority' => [
                ['reading'],
                ['math' => 50, 'science' => 30, 'reading' => 20],
                6.0
            ],
            'no_matches' => [
                ['art'],
                ['math' => 50, 'science' => 30, 'reading' => 20],
                0.0
            ]
        ];
    }
}