<?php

namespace App\Modules\Company\Models;

use App\Modules\Company\Data\CompanyStoreData;
use App\Modules\Company\Data\CompanyUpdateData;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property int $parent_company_id
 * @property string $name
 * @property null|Company $parent
 * @property Company[] $children
 * @property array $descendents
 */
class Company extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['descendents'];

    final public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_company_id', 'id');
    }

    final public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_company_id', 'id');
    }

    public static function createFromData(CompanyStoreData $data): self
    {
        $company = new self;

        $company->name = $data->name;
        $company->parent_company_id = $data->parent_company_id;

        return $company;
    }

    final public function updateFromData(CompanyUpdateData $data): self
    {
        $this->name = $data->name;
        $this->parent_company_id = $data->parent_company_id;

        return $this;
    }

    final protected function descendents(): Attribute
    {
        return Attribute::make(
            get: function () {
                $descendantIds = [];
                $this->loadDescendantsIds($descendantIds);
                $descendantIds[] = $this->id;

                return $descendantIds;
            }
        );
    }

    private function loadDescendantsIds(array &$descendantIds): void
    {
        foreach ($this->children as $descendant) {
            $descendantIds[] = $descendant->id;
            $descendant->loadDescendantsIds($descendantIds);
        }
    }

    final public static function getCacheKey(int $id): string
    {
        return 'company:'.$id;
    }

    final public static function getCollectionCacheKey(): string
    {
        return 'companies';
    }
}
