<?php

declare(strict_types=1);

namespace Recruitment\Tests\Service;

use Recruitment\Model\DtoProvider;
use Recruitment\Model\TopicRequest;
use Recruitment\Service\QuoteCalculator;
use PHPUnit\Framework\TestCase;

class QuoteCalculatorTest extends TestCase
{
    private QuoteCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new QuoteCalculator();
    }

    public function testCalculateQuoteForTwoMatches(): void
    {
        $provider = new DtoProvider('test_provider', ['math', 'science']);
        $request = new TopicRequest([
            'math' => 50,
            'science' => 30,
            'reading' => 20
        ]);

        $quote = $this->calculator->calculateQuote($provider, $request);
        
        $this->assertEqualsWithDelta(8.0, $quote, 0.01);
    }

    public function testCalculateQuoteForSingleMatch(): void
    {
        $provider = new DtoProvider('test_provider', ['math']);
        $request = new TopicRequest([
            'math' => 50,
            'science' => 30,
            'reading' => 20
        ]);

        $quote = $this->calculator->calculateQuote($provider, $request);
        
        $this->assertEqualsWithDelta(10, $quote, 0.01);
    }

    public function testCalculateQuoteForNoMatches(): void
    {
        $provider = new DtoProvider('test_provider', ['art']);
        $request = new TopicRequest([
            'math' => 50,
            'science' => 30,
            'reading' => 20
        ]);

        $quote = $this->calculator->calculateQuote($provider, $request);
        
        $this->assertEquals(0.0, $quote);
    }
}