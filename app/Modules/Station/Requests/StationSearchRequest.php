<?php

declare(strict_types=1);


namespace App\Modules\Station\Requests;

use App\Modules\Company\Models\Company;
use App\Modules\Shared\Requests\AbstractFormRequest;
use App\Modules\Station\Data\StationSearchData;
use App\Modules\Station\ValueObjects\LatitudeValueObject;
use App\Modules\Station\ValueObjects\LongitudeValueObject;

final class StationSearchRequest extends AbstractFormRequest
{
    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'company_id' => 'required|int|exists:'.Company::class.',id',
            'radius' => 'required|int'
        ];
    }

    public function toData(): StationSearchData
    {
        $page = $this->input('page') ?? "1";

        return new StationSearchData(
            page: (string)$page,
            latitude: LatitudeValueObject::make($this->input('latitude')),
            longitude: LongitudeValueObject::make($this->input('longitude')),
            company_id: (int)$this->input('company_id'),
            radius: (int)$this->input('radius')
        );
    }
}