<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\ResponseResource;
use App\Models\Assis;
use App\Models\Episode;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function addToEpisode(Request $request, int $assis_id): JsonResponse
    {
        try {
            $assis = Assis::query()->findOrFail($assis_id);

            $episode = Episode::query()->create([
                'assis_id' => $assis['id'],
                'episode' => $request->input('episode'),
                'comment' => $request->input('comment') ?? null,
                'date' => date('Y-m-d'),
            ]);

            return response()->json(ResponseResource::make($episode), 201);
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function removeFromEpisode(int $assis_id, int $episode): JsonResponse
    {
        try {
            $episode = Episode::query()
                ->where('assis_id', $assis_id)
                ->where('episode', $episode)
                ->delete();

            return response()->json(ResponseResource::make($episode));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
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
                ->where('date', $date)
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

            return response()->json(ResponseResource::make($response));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }
}
