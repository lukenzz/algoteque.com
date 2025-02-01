<?php

declare(strict_types=1);

namespace Recruitment\Service;

use Recruitment\Model\DtoProvider;
use Recruitment\Model\TopicRequest;

class QuoteCalculator
{
    public function calculateQuote(DtoProvider $provider, TopicRequest $request): float
    {
        $topThreeTopics = $request->getTopThreeTopics();
        $matchingTopics = array_intersect($provider->getTopics(), array_keys($topThreeTopics));
        $matchCount = count($matchingTopics);
        if ($matchCount === 0) {
            return 0.0;
        }

        if ($matchCount === 2) {
            $total = 0;
            foreach ($matchingTopics as $topic) {
                $total += $request->getTopicCount($topic);
            }
            return $total * 0.10;
        }

        if ($matchCount === 1) {
            $topic = reset($matchingTopics);
            $position = array_search($topic, array_keys($topThreeTopics));
            return match($position) {
                0 => $request->getTopicCount($topic) * 0.20, // Highest
                1 => $request->getTopicCount($topic) * 0.25, // Second highest
                2 => $request->getTopicCount($topic) * 0.30, // Third highest
                default => 0.0
            };
        }

        return 0.0;
    }
}