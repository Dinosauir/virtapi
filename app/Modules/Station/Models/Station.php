<?php

namespace App\Modules\Station\Models;

use App\Modules\Company\Models\Company;
use App\Modules\Station\Data\StationStoreData;
use App\Modules\Station\Data\StationUpdateData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property-read int $id
 * @property string $name
 * @property $location
 * @property int $company_id
 * @property string $address
 * @property float $latitude
 * @property float $longitude
 * @property Company $company
 */
class Station extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    final public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public static function createFromData(StationStoreData $data): self
    {
        $station = new self;

        $station->name = $data->name;
        $station->address = $data->address;
        $station->location = DB::raw("POINT({$data->longitude->value()}, {$data->latitude->value()})");
        $station->company_id = $data->company_id;
        $station->latitude = $data->latitude->value();
        $station->longitude = $data->longitude->value();

        return $station;
    }

    final public function updateFromData(StationUpdateData $data): self
    {
        $this->name = $data->name;
        $this->address = $data->address;
        $this->location = DB::raw("POINT({$data->longitude->value()}, {$data->latitude->value()})");
        $this->latitude = $data->latitude->value();
        $this->longitude = $data->longitude->value();
        $this->company_id = $data->company_id;

        return $this;
    }

    public static function getCacheKey(int $id): string
    {
        return 'station:'.$id;
    }

    public static function getCollectionCacheKey(): string
    {
        return 'stations';
    }
}
