<?php

declare(strict_types=1);


namespace App\Modules\Station\Data;

use App\Modules\Shared\Data\AbstractData;

final class StationListData extends AbstractData
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