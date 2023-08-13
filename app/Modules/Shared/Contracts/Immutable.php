<?php

declare(strict_types=1);

namespace App\Modules\Shared\Contracts;

interface Immutable
{
    public const IMMUTABLE_MESSAGE = 'Cannot modify attributes.';

    public function __set(string $name, mixed $value): void;
}
