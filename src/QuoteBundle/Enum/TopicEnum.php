<?php

declare(strict_types=1);

namespace App\QuoteBundle\Enum;

enum TopicEnum: string
{
    case HIGHEST = '0.20';
    case SECOND = '0.25';
    case THIRD = '0.30';
    case TWO_TOPICS = '0.10';

    public function value(): float
    {
        return (float) $this->value;
    }
}