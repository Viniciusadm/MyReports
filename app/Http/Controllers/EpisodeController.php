<?php

namespace App\Http\Controllers;

use App\Models\Assis;
use App\Models\Episode;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function addToEpisode(Request $request, int $id_assis): JsonResponse
    {
        try {
            $assis = Assis::query()->findOrFail($id_assis);

            $episode = Episode::query()->create([
                'assis_id' => $assis['id'],
                'episode' => $request->input('episode'),
                'comment' => $request->input('comment') ?? null,
            ]);

            return response()->json(['success' => true, 'data' => $episode]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getByDate(string $date): JsonResponse
    {
        try {
            $episodes = Episode::query()
                ->with('assis', function ($query) {
                    $query->select('id', 'collection_id', 'name', 'hidden_collection', 'average_time', 'type', 'year')
                        ->with('collection', function ($query) {
                            $query->select('id', 'name');
                        });
                })
                ->where('created_at', 'like', $date.'%')
                ->get();

            $time = 0;

            foreach ($episodes as $episode) {
                $time += $episode['assis']['average_time'];
            }

            $total = Episode::query()
                ->selectRaw('count(*) as total')
                ->first()['total'];

            $response = [
                'episodes' => $episodes,
                'total' => $total,
                'time' => $time,
            ];

            return response()->json(['success' => true, 'data' => $response]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
