<?php

declare(strict_types=1);

namespace App\QuoteBundle\Model;

use App\QuoteBundle\Interface\DtoProviderInterface;

class DtoProvider implements DtoProviderInterface
{
    public function __construct(
        private readonly string $name,
        private readonly array $topics,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getTopics(): array
    {
        return $this->topics;
    }

    public function matchesTopics(array $requestedTopics): int
    {
        return count(array_intersect($this->topics, array_keys($requestedTopics)));
    }
}