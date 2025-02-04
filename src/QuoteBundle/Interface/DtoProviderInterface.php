<?php

declare(strict_types=1);

namespace App\QuoteBundle\Interface;

interface DtoProviderInterface
{
    public function getName(): string;
    public function getTopics(): array;
    public function matchesTopics(array $requestedTopics): int;
}