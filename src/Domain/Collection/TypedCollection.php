<?php

namespace App\Domain\Collection;

use InvalidArgumentException;

abstract class TypedCollection extends Collection
{
    public function __construct(array $items = [])
    {
        $type = $this->type();
        foreach ($items as $item) {
            if (!$item instanceof $type) {
                throw new InvalidArgumentException('Invalid class');
            }
        }

        parent::__construct($items);
    }

    abstract protected function type(): string;

    abstract public function items(): array;

    public function add(mixed $item): void
    {
        $type = $this->type();
        if (!$item instanceof $type) {
            throw new InvalidArgumentException('Invalid class');
        }

        parent::add($item);
    }

    public function remove(mixed $item): void
    {
        if (get_class($item) !== $this->type()) {
            throw new InvalidArgumentException('Invalid class');
        }

        parent::remove($item);
    }

    public function contains(mixed $item): bool
    {
        if (get_class($item) !== $this->type()) {
            throw new InvalidArgumentException('Invalid class');
        }

        return parent::contains($item);
    }
}
