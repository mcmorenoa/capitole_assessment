<?php

namespace App\Domain\Collection;

interface MatchCriteriaInterface
{
    public function matches(mixed $item): bool;
}
