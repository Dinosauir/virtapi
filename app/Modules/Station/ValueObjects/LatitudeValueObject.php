<?php

declare(strict_types=1);


namespace App\Modules\Station\ValueObjects;

use App\Modules\Shared\ValueObjects\AbstractValueObject;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LatitudeValueObject extends AbstractValueObject
{
    protected readonly float $latitude;

    /**
     * @throws ValidationException
     */
    public function __construct(mixed $number)
    {
        if (isset($this->latitude)) {
            throw new \InvalidArgumentException(
                static::IMMUTABLE_MESSAGE,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->latitude = (float)$number;

        $this->validate();
    }

    final public function toArray(): array
    {
        return [
            'latitude' => $this->latitude
        ];
    }

    final public function value(): float
    {
        return $this->latitude;
    }

    /**
     * @throws ValidationException
     */
    private function validate(): void
    {
        if ($this->latitude <= -90 || $this->latitude >= 90) {
            throw ValidationException::withMessages(['Latitude is invalid']);
        }
    }
}