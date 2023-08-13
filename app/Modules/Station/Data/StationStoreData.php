<?php

declare(strict_types=1);


namespace App\Modules\Station\Data;

use App\Modules\Shared\Data\AbstractData;
use App\Modules\Station\Requests\StationStoreRequest;
use App\Modules\Station\ValueObjects\LatitudeValueObject;
use App\Modules\Station\ValueObjects\LongitudeValueObject;

final class StationStoreData extends AbstractData
{
    public function __construct(
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
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'company_id' => $this->company_id,
            'address' => $this->address
        ];
    }
}