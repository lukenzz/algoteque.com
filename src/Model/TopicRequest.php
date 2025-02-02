<?php

declare(strict_types=1);

namespace Recruitment\Model;

class TopicRequest
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