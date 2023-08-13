<?php

namespace App\Modules\Company\Resources;

use App\Modules\Company\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        assert($this->resource instanceof Company);

        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'descendents' => $this->when($this->resource->children->count() !== 0, $this->resource->descendents),
            'parent' => self::make($this->resource->parent)
        ];
    }
}
