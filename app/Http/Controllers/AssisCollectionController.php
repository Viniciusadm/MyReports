<?php

namespace App\Http\Controllers;

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

            return response()->json(['success' => true, 'data' => $collection]);
        } catch (Exception $e) {
            return response()->json(['sucess' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function showCollection(int $id): JsonResponse
    {
        try {
            $collection = AssisCollection::query()
                ->findOrFail($id);

            return response()->json(['success' => true, 'data' => $collection]);
        } catch (Exception $e) {
            return response()->json(['sucess' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function newCollection(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            $collection = AssisCollection::query()->create([
                'name' => $data['name'],
                'image' => $data['image'] ?? null,
            ]);

            $assis = Assis::query()->create([
                'collection_id' => $collection['id'],
                'name' => $data['assis']['name'] ?? null,
                'total' => $data['assis']['total'],
                'type' => $data['assis']['type'],
                'status' => $data['assis']['status'],
                'order' => 0,
                'average_time' => $data['assis']['average_time'] ?? null,
                'image' => $data['assis']['image'] ?? null,
                'year' => $data['assis']['year'] ?? null,
                'sinopse' => $data['assis']['sinopse'] ?? null,
                'hidden_collection' => $data['assis']['hidden_collection'] ?? null,
            ]);

            return response()->json(['success' => true, 'data' => $assis]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function addToCollection(Request $request, int $id_collection): JsonResponse
    {
        try {
            $collection = AssisCollection::query()->findOrFail($id_collection);

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

            return response()->json(['success' => true, 'data' => $assis]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
