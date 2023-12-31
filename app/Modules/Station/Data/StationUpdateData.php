<?php

declare(strict_types=1);

namespace App\Modules\Station\Data;

use App\Modules\Shared\Data\AbstractData;
use App\Modules\Station\ValueObjects\LatitudeValueObject;
use App\Modules\Station\ValueObjects\LongitudeValueObject;

final class StationUpdateData extends AbstractData
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly LatitudeValueObject $latitude,
        public readonly LongitudeValueObject $longitude,
        public readonly int $company_id,
        public readonly string $address
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'latitude' => $this->latitude->value(),
            'longitude' => $this->longitude->value(),
            'company_id' => $this->company_id,
            'address' => $this->address
        ];
    }
}
