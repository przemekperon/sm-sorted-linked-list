<?php

declare(strict_types=1);

namespace PrzemekPeron;

/**
 * @internal
 */
final class Node
{
    public ?Node $next = null;

    public function __construct(
        public readonly int|string $value,
    ) {}
}
