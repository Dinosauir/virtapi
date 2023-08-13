<?php

declare(strict_types=1);

namespace App\Modules\Shared\ValueObjects;

use App\Modules\Shared\Contracts\Immutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;

abstract class AbstractValueObject implements Arrayable, Immutable
{
    use Macroable;
    use Conditionable;

    abstract public function value(): mixed;

    public static function make(mixed ...$values): static
    {
        return new static(...$values);
    }

    public static function from(mixed ...$values): static
    {
        return static::make(...$values);
    }

    public static function makeOrNull(mixed ...$values): static|null
    {
        try {
            return static::make(...$values);
        } catch (\Throwable) {
            return null;
        }
    }

    public function equals(self $object): bool
    {
        return $this === $object;
    }

    public function notEquals(self $object): bool
    {
        return !$this->equals($object);
    }

    public function toArray(): array
    {
        return (array)$this->value();
    }

    public function toString(): string
    {
        return (string)$this->value();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function __get(string $name): mixed
    {
        return $this->{$name};
    }

    public function __set(string $name, mixed $value): void
    {
        throw new \InvalidArgumentException(__(static::IMMUTABLE_MESSAGE));
    }
}
