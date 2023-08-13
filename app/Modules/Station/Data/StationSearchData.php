<?php

declare(strict_types=1);


namespace App\Modules\Station\Data;

use App\Modules\Shared\Data\AbstractData;
use App\Modules\Station\ValueObjects\LatitudeValueObject;
use App\Modules\Station\ValueObjects\LongitudeValueObject;

class StationSearchData extends AbstractData
{
    public function __construct(
        public readonly string $page,
        public readonly LatitudeValueObject $latitude,
        public readonly LongitudeValueObject $longitude,
        public readonly int $company_id,
        public readonly int $radius
    ) {
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'latitude' => $this->latitude->value(),
            'longitude' => $this->longitude->value(),
            'company_id' => $this->company_id,
            'radius' => $this->radius
        ];
    }
}