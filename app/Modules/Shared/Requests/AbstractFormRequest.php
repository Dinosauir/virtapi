<?php

declare(strict_types=1);

namespace App\Modules\Shared\Requests;

use App\Modules\Shared\Concerns\HasRouteParamValidationTrait;
use App\Modules\Shared\Data\AbstractData;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractFormRequest extends FormRequest
{
    use HasRouteParamValidationTrait;

    public function authorize(): bool
    {
        return true;
    }

    abstract public function toData(): AbstractData;
}
