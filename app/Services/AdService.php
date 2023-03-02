<?php

namespace App\Services;

use App\Repositories\AdRepository;

class AdService extends BaseService
{

    /**
     * @inheritDoc
     */
    public function getRepository(): string
    {
        return AdRepository::class;
    }

    /**
     * Get ads by sort order and per page.
     *
     * @param  string  $sortBy
     * @param  string  $sortOrder
     * @param  int  $perPage
     * @return mixed
     */
    public function getAds(string $sortBy = 'created_at', string $sortOrder = 'desc', int $perPage = 10): mixed
    {
        return $this->repository->getAds($sortBy, $sortOrder, $perPage);
    }

    /**
     * Get ad details by id.
     *
     * @param  int  $id
     * @param  string  $fields
     * @return array
     */
    public function getAd(int $id, string $fields): array
    {
        $ad = $this->getById($id);
        $result = [
            'id' => $ad->id,
            'title' => $ad->title,
            'price' => $ad->price,
            'main_picture' => $ad->pictures[0],
        ];

        if ($fields) {
            $requestedFields = explode(',', $fields);

            if (in_array('description', $requestedFields)) {
                $result['description'] = $ad->description;
            }

            if (in_array('all_pictures_url', $requestedFields)) {
                $result['all_pictures_url'] = $ad->pictures;
            }
        }

        return $result;
    }

    /**
     * Create new ad instance.
     *
     * @param array $data
     * @return array
     */
    public function createAd(array $data): array
    {
        $ad = $this->repository->create($data);

        return [
            'id' => $ad->id,
            'result' => 'success',
        ];
    }
}
