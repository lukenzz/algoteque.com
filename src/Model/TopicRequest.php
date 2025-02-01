<?php

declare(strict_types=1);

namespace Recruitment\Model;

class TopicRequest
{
    private array $topics;

    public function __construct(array $topics)
    {
        $this->topics = $topics;
    }

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