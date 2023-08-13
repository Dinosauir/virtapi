<?php

declare(strict_types=1);

namespace App\Modules\Company\Requests;

use App\Modules\Company\Data\CompanyListData;
use App\Modules\Shared\Requests\AbstractFormRequest;

class CompanyListRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'page' => 'nullable|int'
        ];
    }

    public function toData(): CompanyListData
    {
        return new CompanyListData(
            page: $this->input('page') ?? 1
        );
    }
}
