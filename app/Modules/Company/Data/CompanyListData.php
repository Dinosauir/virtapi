<?php

declare(strict_types=1);

namespace App\Modules\Company\Data;

use App\Modules\Shared\Data\AbstractData;

class CompanyListData extends AbstractData
{
    public function __construct(public readonly int $page)
    {
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page
        ];
    }
}
