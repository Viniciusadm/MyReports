<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\Episode;
use App\Models\Participant;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function participants(): JsonResponse
    {
        $participants = Participant::query()
            ->selectRaw('count(*) as participations, people.nickname')
            ->join('people', 'people.id', '=', 'participants.person_id')
            ->groupBy('people.id')
            ->orderBy('participations', 'desc')
            ->limit(10)
            ->get();

        return response()->json(ResponseResource::make($participants));
    }

    public function minutes(): JsonResponse
    {
        $minutesQuery = Episode::query()
            ->selectRaw('sum(assis.average_time) as minutes, date')
            ->join('assis', 'assis.id', '=', 'episodes.assis_id')
            ->orderBy('date', 'desc')
            ->groupBy('date')
            ->take(10)
            ->get();

        $minutes = [];

        foreach ($minutesQuery as $minute) {
            $minutes[$minute->date] = $minute->minutes;
        }

        return response()->json(ResponseResource::make($minutes));
    }

    public function episodes(): JsonResponse
    {
        $episodes = Episode::query()
            ->selectRaw('count(episodes.id) as count, assis_collections.name as name, assis_collections.id as collection_id')
            ->join('assis', 'assis.id', '=', 'episodes.assis_id')
            ->join('assis_collections', 'assis_collections.id', '=', 'assis.collection_id')
            ->groupBy('collection_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();


        return response()->json(ResponseResource::make($episodes));
    }
}
