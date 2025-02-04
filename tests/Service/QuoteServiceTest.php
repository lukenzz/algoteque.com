<?php

declare(strict_types=1);

namespace App\QuoteBundle\Tests\Service;

use App\QuoteBundle\Exception\InvalidConfigurationException;
use App\QuoteBundle\Exception\InvalidRequestException;
use App\QuoteBundle\Interface\QuoteCalculatorInterface;
use App\QuoteBundle\Service\QuoteService;
use App\QuoteBundle\Service\QuoteCalculator;
use App\Service\LoggerService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Monolog\Logger;
use Monolog\Handler\TestHandler;

class QuoteServiceTest extends TestCase
{
    private QuoteService $service;
    private MockObject|QuoteCalculatorInterface $calculator;
    private MockObject|LoggerService $logger;

    protected function setUp(): void
    {
        $this->calculator = $this->createMock(QuoteCalculatorInterface::class);
        $this->logger = $this->createMock(LoggerService::class);

        $this->service = new QuoteService(
            $this->calculator,
            $this->logger
        );
    }

    public function testLoadProvidersSuccessfully(): void
    {
        $configPath = sys_get_temp_dir() . '/test_config.json';
        $configData = [
            'provider_topics' => [
                'provider_a' => 'math|science',
                'provider_b' => 'reading|science'
            ]
        ];
        file_put_contents($configPath, json_encode($configData));

        $this->logger
            ->expects($this->once())
            ->method('logMessage')
            ->with($this->stringContains('Providers generated successfully: count 2'));

        $this->service->loadProviders($configPath);
        unlink($configPath);
    }

    public function testGenerateQuotesSuccessfully(): void
    {
        $configPath = sys_get_temp_dir() . '/test_config.json';
        $configData = [
            'provider_topics' => [
                'provider_a' => 'math|science',
                'provider_b' => 'reading|science'
            ]
        ];
        file_put_contents($configPath, json_encode($configData));
        $this->service->loadProviders($configPath);

        $request = [
            'topics' => [
                'math' => 50,
                'science' => 30
            ]
        ];

        $this->calculator
            ->expects($this->exactly(2))
            ->method('calculateQuote')
            ->willReturnOnConsecutiveCalls(8.0, 5.0);

        $this->logger
            ->expects($this->once())
            ->method('logMessage')
            ->with($this->stringContains('Quotes generated successfully'));

        $quotes = $this->service->generateQuotes($request);

        $this->assertEquals(8.0, $quotes['provider_a']);
        $this->assertEquals(5.0, $quotes['provider_b']);

        unlink($configPath);
    }

    public function testLoadProvidersWithInvalidConfig(): void
    {
        $configPath = sys_get_temp_dir() . '/invalid_config.json';
        file_put_contents($configPath, '{"invalid": "config"}');

        $this->expectException(InvalidConfigurationException::class);
        $this->service->loadProviders($configPath);
        unlink($configPath);
    }

    public function testLoadProvidersWithNonExistentFile(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->service->loadProviders('/non/existent/path.json');
    }

    public function testGenerateQuotesWithEmptyTopics(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('Topics cannot be empty');
        $this->service->generateQuotes(['topics' => []]);
    }

    public function testGenerateQuotesWithInvalidTopicCount(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('Topic count must be a positive integer');
        $this->service->generateQuotes([
            'topics' => [
                'math' => -1
            ]
        ]);
    }

    public function testGenerateQuotesWithNoMatches(): void
    {
        $configPath = sys_get_temp_dir() . '/test_config.json';
        $configData = [
            'provider_topics' => [
                'provider_a' => 'math|science'
            ]
        ];
        file_put_contents($configPath, json_encode($configData));
        $this->service->loadProviders($configPath);

        $request = [
            'topics' => [
                'art' => 50,
                'music' => 30
            ]
        ];

        $this->calculator
            ->method('calculateQuote')
            ->willReturn(0.0);

        $quotes = $this->service->generateQuotes($request);
        $this->assertEmpty($quotes);

        unlink($configPath);
    }
}