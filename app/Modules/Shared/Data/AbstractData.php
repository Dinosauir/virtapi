<?php

declare(strict_types=1);


namespace App\Modules\Shared\Data;

use App\Modules\Shared\Contracts\Immutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class AbstractData implements Arrayable, Immutable
{
    public function __set(string $name, mixed $value): void
    {
        throw new \InvalidArgumentException(__(static::IMMUTABLE_MESSAGE));
    }
}