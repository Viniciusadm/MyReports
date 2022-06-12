<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\ResponseResource;
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

            foreach ($assis as $assi) {
                $assi->append('full_name');
                $assi->append('image_url');
                $assi->append('type_formatted');
            }

            return response()->json(ResponseResource::make($assis));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
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
                ->findOrFail($id);

            $assis->append('weekday_formatted');

            return response()->json(ResponseResource::make($assis));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function changeStatus(Request $request, int $id): JsonResponse
    {
        try {
            $assis = Assis::query()
                ->findOrFail($id)
                ->update([
                    'status' => $request->input('status')
                ]);

            return response()->json(ResponseResource::make($assis));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function finishAiring(int $id): JsonResponse
    {
        try {
            $assis = Assis::query()
                ->findOrFail($id)
                ->update([
                    'airing' => false
                ]);

            return response()->json(ResponseResource::make($assis));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }
}
