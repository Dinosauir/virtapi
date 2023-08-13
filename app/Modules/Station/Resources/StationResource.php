<?php

namespace App\Modules\Station\Resources;

use App\Modules\Company\Resources\CompanyResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class StationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if (is_array($this->resource)) {
            $arr = [];
            foreach ($this->resource as $station) {
                $arr[] = [
                    'id' => $station->id,
                    'name' => $station->name,
                    'latitude' => $station->latitude,
                    'longitude' => $station->longitude,
                    'address' => $station->address,
                    'distance' => number_format($station->distance / 1000, 2),
                    'company' => CompanyResource::make($station->company)
                ];
            }

            return $arr;
        }

        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'latitude' => $this->resource->latitude,
            'longitude' => $this->resource->longitude,
            'address' => $this->resource->address,
            'company' => CompanyResource::make($this->resource->company)
        ];
    }
}
