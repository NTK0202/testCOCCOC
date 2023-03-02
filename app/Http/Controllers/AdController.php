<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdRequest;
use App\Models\Ad;
use App\Services\AdService;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdController extends Controller
{
    protected $adService;

    public function __construct(AdService $adService)
    {
        $this->adService = $adService;
    }
    /**
     * Get a list of ads.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $perPage = 10;
        $ads = $this->adService->getAds($sortBy, $sortOrder, $perPage);

        return response()->json($ads);
    }

    /**
     * Get a specific ad.
     *
     * @param  int  $id
     * @param  Request  $request
     * @return JsonResponse
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $fields = $request->input('fields');
        $ad = $this->adService->getAd($id, $fields);

        return response()->json($ad);
    }

    /**
     * Create a new ad.
     *
     * @param  AdRequest  $request
     * @return JsonResponse
     */
    public function store(AdRequest $request): JsonResponse
    {
        $ad = $this->adService->createAd($request->all());

        return response()->json($ad);
    }
}
