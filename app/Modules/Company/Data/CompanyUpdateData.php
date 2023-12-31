<?php

declare(strict_types=1);

namespace App\Modules\Company\Data;

use App\Modules\Shared\Data\AbstractData;

final class CompanyUpdateData extends AbstractData
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int|null $parent_company_id = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_company_id' => $this->parent_company_id
        ];
    }
}
