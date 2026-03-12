<?php

declare(strict_types=1);

namespace PrzemekPeron;

use Countable;
use Generator;
use InvalidArgumentException;
use IteratorAggregate;

/** @implements IteratorAggregate<int, int|string> */
final class SortedLinkedList implements Countable, IteratorAggregate
{
    private ?Node $head = null;

    private int $count = 0;

    private ?string $type = null;

    public function __construct(
        private readonly SortDirection $direction = SortDirection::ASC,
    ) {}

    /** @throws InvalidArgumentException */
    public function insert(int|string $value): void
    {
        $this->guardType($value);

        $node = new Node($value);

        if ($this->head === null || $this->comesBefore($value, $this->head->value)) {
            $node->next = $this->head;
            $this->head = $node;
        } else {
            $current = $this->head;

            while ($current->next !== null && $this->comesBefore($current->next->value, $value)) {
                $current = $current->next;
            }

            $node->next = $current->next;
            $current->next = $node;
        }

        $this->count++;
    }

    public function remove(int|string $value): bool
    {
        if ($this->head === null || !$this->isMatchingType($value)) {
            return false;
        }

        $found = false;

        if ($this->head->value === $value) {
            $this->head = $this->head->next;
            $this->count--;

            if ($this->count === 0) {
                $this->type = null;
            }

            $found = true;
        } else {
            $current = $this->head;

            while ($current->next !== null) {
                if ($current->next->value === $value) {
                    $current->next = $current->next->next;
                    $this->count--;
                    $found = true;
                    break;
                }

                if ($this->comesAfter($current->next->value, $value)) {
                    break;
                }

                $current = $current->next;
            }
        }

        return $found;
    }

    public function contains(int|string $value): bool
    {
        if (!$this->isMatchingType($value)) {
            return false;
        }

        $current = $this->head;

        while ($current !== null) {
            if ($current->value === $value) {
                return true;
            }

            if ($this->comesAfter($current->value, $value)) {
                break;
            }

            $current = $current->next;
        }

        return false;
    }

    /** @return list<int|string> */
    public function toArray(): array
    {
        $result = [];
        $current = $this->head;

        while ($current !== null) {
            $result[] = $current->value;
            $current = $current->next;
        }

        return $result;
    }

    public function isEmpty(): bool
    {
        return $this->head === null;
    }

    /** @return int<0, max> */
    public function count(): int
    {
        return max($this->count, 0);
    }

    /** @return Generator<int, int|string> */
    public function getIterator(): Generator
    {
        $current = $this->head;
        $index = 0;

        while ($current !== null) {
            yield $index++ => $current->value;
            $current = $current->next;
        }
    }

    private function guardType(int|string $value): void
    {
        $type = get_debug_type($value);

        if ($this->type === null) {
            $this->type = $type;

            return;
        }

        if ($this->type !== $type) {
            throw new InvalidArgumentException(
                sprintf('Cannot add %s value to a list of %s.', $type, $this->type)
            );
        }
    }

    private function isMatchingType(int|string $value): bool
    {
        return $this->type === null || $this->type === get_debug_type($value);
    }

    private function comesBefore(int|string $a, int|string $b): bool
    {
        return $this->direction === SortDirection::ASC ? $a < $b : $a > $b;
    }

    private function comesAfter(int|string $a, int|string $b): bool
    {
        return $this->direction === SortDirection::ASC ? $a > $b : $a < $b;
    }
}
