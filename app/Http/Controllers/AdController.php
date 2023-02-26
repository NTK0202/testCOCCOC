<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdRequest;
use App\Models\Ad;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $ads = Ad::orderBy($request->get('sort_by', 'created_at'), $request->get('sort_order', 'desc'))
            ->paginate(10);

        return response()->json([
            'data' => $ads->map(function (Ad $ad) {
                return [
                    'id' => $ad->id,
                    'title' => $ad->title,
                    'main_picture' => $ad->pictures[0],
                    'price' => $ad->price,
                ];
            }),
            'meta' => [
                'total' => $ads->total(),
            ],
        ]);
    }

    public function show($id, Request $request): JsonResponse
    {
        $ad = Ad::findOrFail($id);

        $fields = $request->input('fields');

        $response = [
            'title' => $ad->title,
            'price' => $ad->price,
            'main_picture' => $ad->pictures[0],
        ];

        if ($fields && in_array('description', explode(',', $fields))) {
            $response['description'] = $ad->description;
        }

        if ($fields && in_array('all_pictures_url', explode(',', $fields))) {
            $response['all_pictures_url'] = $ad->pictures;
        }

        return response()->json($response);
    }

    public function store(AdRequest $request): JsonResponse
    {
        $ad = new Ad();

        $ad->title = $request->get('title');
        $ad->description = $request->get('description');
        $ad->price = $request->get('price');
        $ad->pictures = $request->get('pictures');

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
