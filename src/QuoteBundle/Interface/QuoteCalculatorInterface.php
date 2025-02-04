<?php

declare(strict_types=1);

namespace App\QuoteBundle\Interface;

use App\QuoteBundle\Model\DtoProvider;
use App\QuoteBundle\Model\TopicRequest;

interface QuoteCalculatorInterface
{
    public function calculateQuote(DtoProvider $dtoProvider, TopicRequest $request): float;
}