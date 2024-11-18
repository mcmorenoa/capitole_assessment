<?php

namespace App\Domain\Collection;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Collection implements IteratorAggregate, \JsonSerializable
{
    public function __construct(protected array $items = [])
    {
    }

    public function filter(callable $fn): static
    {
        return new static(array_filter($this->items, $fn, ARRAY_FILTER_USE_BOTH));
    }

    public function first(): mixed
    {
        return reset($this->items);
    }

    public function last(): mixed
    {
        return end($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    public function remove(mixed $item): void
    {
        $index = array_search($item, $this->items, true);
        if ($index !== false) {
            array_splice($this->items, $index, 1);
        }
    }

    public function contains(mixed $item): bool
    {
        return in_array($item, $this->items, true);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function findFirst(MatchCriteriaInterface $criteria): mixed
    {
        foreach ($this->items as $item) {
            if ($criteria->matches($item)) {
                return $item;
            }
        }
        return null;
    }

    public function hasOneElement(): bool
    {
        return $this->count() === 1;
    }

    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
