<?php

namespace App\Contracts;

use App\DTOs\CardMetadata;

interface Card
{
    public function getCardMetadata(): CardMetadata;
    public function getName(): string;
    public function getPrompt(): ?string;
    public function getType(): string;
}
