<?php

declare(strict_types=1);

namespace Recruitment\Model;

class DtoProvider
{
    public function __construct(
        private string $name,
        private array  $topics
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getTopics(): array
    {
        return $this->topics;
    }
}