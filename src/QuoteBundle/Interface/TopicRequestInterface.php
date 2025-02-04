<?php

declare(strict_types=1);

namespace App\QuoteBundle\Interface;

interface TopicRequestInterface
{
    public function getTopThreeTopics(): array;

    public function getTopicCount(string $topic): int;
}