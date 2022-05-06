<?php

namespace App\Http\Controllers;

use App\Models\Assis;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssisController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $status = request()->input('status');
            $type = request()->input('type');

            $query = Assis::query()
                ->select('id', 'collection_id', 'name', 'total', 'status', 'created_at', 'type', 'image', 'hidden_collection')
                ->with('collection', function ($query) {
                    $query->select('id', 'name', 'image');
                })
                ->withCount('episodes');

            if ($status) {
                $query->where('status', $status);
            }

            if ($type) {
                $query->where('type', $type);
            }

            $assis = $query->get();

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

    public function changeStatus(Request $request, int $id): JsonResponse
    {
        try {
            $assis = Assis::query()->findOrFail($id);
            $assis->update(['status' => $request->input('status')]);

            return response()->json(['success' => true, 'data' => $assis]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
