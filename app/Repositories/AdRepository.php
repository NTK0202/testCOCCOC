<?php

namespace App\Repositories;

use App\Models\Ad;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdRepository extends BaseRepository
{

    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Ad::class;
    }

    /**
     * Retrieve a paginated list of ads.
     *
     * @param  string  $sortField
     * @param  string  $sortOrder
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getAds(string $sortField, string $sortOrder, int $perPage): LengthAwarePaginator
    {
        return
            $this->model
            ->orderBy($sortField, $sortOrder)
            ->paginate($perPage, ['*'], 'page');
    }
}
