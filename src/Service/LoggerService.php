<?php

declare(strict_types=1);

namespace App\Service;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Level;

class LoggerService
{
    public function __construct(private Logger $logger)
    {
    }

    public function logMessage(string $message, string $level = 'info'): void
    {
        $level = ($_ENV['APP_ENV'] === 'dev') ? 'debug' : $level;
        $logEnvDir = ($_ENV['APP_ENV'] === 'dev') ? 'dev' : 'prod';
        $logPath = strtolower($_ENV['LOG_PATH'] ?? 'logs/');

        $levelMapping = [
            'debug' => Level::Debug,
            'info' => Level::Info,
            'notice' => Level::Notice,
            'warning' => Level::Warning,
            'error' => Level::Error,
            'critical' => Level::Critical,
            'alert' => Level::Alert,
            'emergency' => Level::Emergency,
        ];

        $this->logger->pushHandler(
            new RotatingFileHandler(
                __DIR__ . '/../../' . $logPath . $logEnvDir . '/app.log',
                0,
                $levelMapping[$level],
            )
        );
        $this->logger->log($levelMapping[$level], $message);
    }
}