<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\ResponseResource;
use App\Models\Assis;
use App\Models\AssisCollection;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssisCollectionController extends Controller
{
    public function show(int $id): JsonResponse
    {
        try {
            $collection = AssisCollection::query()
                ->with('assis')
                ->findOrFail($id);

            return response()->json(ResponseResource::make($collection));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function showCollection(int $id): JsonResponse
    {
        try {
            $collection = AssisCollection::query()
                ->findOrFail($id);

            return response()->json(ResponseResource::make($collection));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function newCollection(Request $request): JsonResponse
    {
        try {
            $collection = AssisCollection::query()->create([
                'name' => $request->input('name'),
                'image' => $request->input('image'),
            ]);

            $assis = $request->input('assis');

            $assis = Assis::query()->create([
                'collection_id' => $collection['id'],
                'name' => $assis['name'],
                'total' => $assis['total'],
                'type' => $assis['type'],
                'status' => $assis['status'],
                'order' => 0,
                'average_time' => $assis['average_time'] ?? null,
                'image' => $assis['image'] ?? null,
                'year' => $assis['year'] ?? null,
                'sinopse' => $assis['sinopse'] ?? null,
                'hidden_collection' => $assis['hidden_collection'] ?? null,
            ]);

            return response()->json(ResponseResource::make($assis));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function addToCollection(Request $request, int $id_collection): JsonResponse
    {
        try {
            $collection = AssisCollection::query()
                ->findOrFail($id_collection);

            $assis = Assis::query()->create([
                'collection_id' => $collection['id'],
                'name' => $request->input('name'),
                'total' => $request->input('total'),
                'type' => $request->input('type'),
                'status' => $request->input('status'),
                'order' => 0,
                'average_time' => $request->input('average_time'),
                'image' => $request->input('image'),
                'year' => $request->input('year'),
                'sinopse' => $request->input('sinopse'),
                'hidden_collection' => $request->input('hidden_collection', false),
            ]);

            return response()->json(ResponseResource::make($assis));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }
}
