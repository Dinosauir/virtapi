<?php

declare(strict_types=1);


namespace App\Modules\Station\Requests;

use App\Modules\Shared\Data\AbstractData;
use App\Modules\Shared\Requests\AbstractFormRequest;
use App\Modules\Station\Data\StationListData;

class StationListRequest extends AbstractFormRequest
{
    public function rules(): array
    {
        return [
            'page' => 'nullable|int'
        ];
    }

    public function toData(): StationListData
    {
        return new StationListData(
            page: $this->input('page') ?? 1
        );
    }
}