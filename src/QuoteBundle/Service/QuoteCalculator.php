<?php

declare(strict_types=1);

namespace App\QuoteBundle\Service;

use App\QuoteBundle\Enum\TopicEnum;
use App\QuoteBundle\Interface\QuoteCalculatorInterface;
use App\QuoteBundle\Model\DtoProvider;
use App\QuoteBundle\Model\TopicRequest;

class QuoteCalculator implements QuoteCalculatorInterface
{
    public function calculateQuote(DtoProvider $dtoProvider, TopicRequest $request): float
    {
        $topThreeTopics = $request->getTopThreeTopics();
        $matchingTopics = array_intersect($dtoProvider->getTopics(), array_keys($topThreeTopics));
        $matchCount = count($matchingTopics);

        return match($matchCount) {
            2 => $this->calculateTwoTopicsQuote($matchingTopics, $request),
            1 => $this->calculateSingleTopicQuote($matchingTopics, $topThreeTopics, $request),
            default => 0.0
        };
    }

    private function calculateTwoTopicsQuote(array $matchingTopics, TopicRequest $request): float
    {
        $total = 0;
        foreach ($matchingTopics as $topic) {
            $total += $request->getTopicCount($topic);
        }

        return $total * TopicEnum::TWO_TOPICS->value();
    }

    private function calculateSingleTopicQuote(
        array $matchingTopics,
        array $topThreeTopics,
        TopicRequest $request,
    ): float {
        $topic = reset($matchingTopics);
        $position = array_search($topic, array_keys($topThreeTopics));

        return match($position) {
            0 => $request->getTopicCount($topic) * TopicEnum::HIGHEST->value(),
            1 => $request->getTopicCount($topic) * TopicEnum::SECOND->value(),
            2 => $request->getTopicCount($topic) * TopicEnum::THIRD->value(),
            default => 0.0
        };
    }
}