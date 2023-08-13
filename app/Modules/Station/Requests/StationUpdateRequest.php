<?php

namespace App\Modules\Station\Requests;

use App\Modules\Company\Models\Company;
use App\Modules\Shared\Requests\AbstractFormRequest;
use App\Modules\Station\Data\StationUpdateData;
use App\Modules\Station\ValueObjects\LatitudeValueObject;
use App\Modules\Station\ValueObjects\LongitudeValueObject;

final class StationUpdateRequest extends AbstractFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'company_id' => 'required|int|exists:'.Company::class.',id',
            'address' => 'required|string'
        ];
    }

    public function toData(): StationUpdateData
    {
        return new StationUpdateData(
            id: (int)$this->input('station'),
            name: $this->input('name'),
            latitude: LatitudeValueObject::make($this->input('latitude')),
            longitude: LongitudeValueObject::make($this->input('longitude')),
            company_id: (int)$this->input('company_id'),
            address: $this->input('address')
        );
    }

}
