<?php

declare(strict_types=1);


namespace App\Modules\Station\ValueObjects;

use App\Modules\Shared\ValueObjects\AbstractValueObject;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LongitudeValueObject extends AbstractValueObject
{
    protected readonly float $longitude;

    /**
     * @throws ValidationException
     */
    public function __construct(mixed $number)
    {
        if (isset($this->longitude)) {
            throw new \InvalidArgumentException(
                static::IMMUTABLE_MESSAGE,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->longitude = (float)$number;

        $this->validate();
    }

    final public function toArray(): array
    {
        return [
            'longitude' => $this->longitude
        ];
    }

    final public function value(): float
    {
        return $this->longitude;
    }

    /**
     * @throws ValidationException
     */
    private function validate(): void
    {
        if ($this->longitude <= -180 || $this->longitude >= 180) {
            throw ValidationException::withMessages(['Longitude is invalid']);
        }
    }
}