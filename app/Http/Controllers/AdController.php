<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdRequest;
use App\Models\Ad;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdController extends Controller
{
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

        $ads = Ad::orderBy($sortBy, $sortOrder)
            ->only('id','title','main_picture','price')
            ->paginate(10);

        return response()->json([
            'data' => $ads,
            'meta' => [
                'total' => $ads->total(),
            ]
        ]);
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
        $ad = Ad::findOrFail($id);

        $fields = $request->input('fields');

        $response = [
            'title' => $ad->title,
            'price' => $ad->price,
            'main_picture' => $ad->pictures[0],
        ];

        if ($fields) {
            $requestedFields = explode(',', $fields);

            if (in_array('description', $requestedFields)) {
                $response['description'] = $ad->description;
            }

            if (in_array('all_pictures_url', $requestedFields)) {
                $response['all_pictures_url'] = $ad->pictures;
            }
        }

        return response()->json($response);
    }

    /**
     * Create a new ad.
     *
     * @param  AdRequest  $request
     * @return JsonResponse
     */
    public function store(AdRequest $request): JsonResponse
    {
        $ad = new Ad();

        $ad->fill($request->validated());

        if ($ad->save()) {
            return response()->json([
                'id' => $ad->id,
                'result' => 'success',
            ], Response::HTTP_CREATED);
        } else {
            return response()->json([
                'result' => 'error',
            ], Response::HTTP_NOT_IMPLEMENTED);
        }
    }
}
