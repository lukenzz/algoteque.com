<?php

declare(strict_types=1);

namespace App\QuoteBundle\Model;

use App\QuoteBundle\Interface\TopicRequestInterface;

class TopicRequest implements TopicRequestInterface
{
    public function __construct(
        private array $topics
    ) {}

    public function getTopThreeTopics(): array
    {
        arsort($this->topics);
        return array_slice($this->topics, 0, 3, true);
    }

    public function getTopicCount(string $topic): int
    {
        return $this->topics[$topic] ?? 0;
    }
}