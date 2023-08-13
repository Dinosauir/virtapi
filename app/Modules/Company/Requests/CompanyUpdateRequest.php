<?php

namespace App\Modules\Company\Requests;

use App\Modules\Company\Data\CompanyUpdateData;
use App\Modules\Company\Models\Company;
use App\Modules\Shared\Requests\AbstractFormRequest;

final class CompanyUpdateRequest extends AbstractFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'parent_company_id' => 'nullable|int|exists:' . Company::class . ',id',
        ];
    }

    public function toData(): CompanyUpdateData
    {
        return new CompanyUpdateData(
            id: (int)$this->input('company'),
            name: $this->input('name'),
            parent_company_id: $this->input('parent_company_id') !== null ?
                (int)$this->input('parent_company_id') :
                null
        );
    }
}
