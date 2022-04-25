<?php

namespace App\Http\Controllers;

use App\Models\Assis;
use Exception;
use Illuminate\Http\JsonResponse;

class AssisController extends Controller
{
    public function status(string $status): JsonResponse
    {
        try {
            $assis = Assis::query()
                ->select('id', 'collection_id', 'name', 'total', 'status', 'created_at', 'type', 'image')
                ->with('collection', function ($query) {
                    $query->select('id', 'name', 'image');
                })
                ->withCount('episodes')
                ->where('status', $status)
                ->get();

            return response()->json(['success' => true, 'data' => $assis]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $assis = Assis::query()
                ->with('collection', function ($query) {
                    $query->select('id', 'name');
                })
                ->with('episodes')
                ->where('id', $id)
                ->first();

            return response()->json(['success' => true, 'data' => $assis]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
