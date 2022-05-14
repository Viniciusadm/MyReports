<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $dates = $request->input('dates', []);
        $participants = $this->countParticipants();
        $minutes = $this->minutesAssis($dates);

        return response()->json([
            'success' => true,
            'data' => [
                'participants' => $participants,
                'minutes' => $minutes,
            ],
        ]);
    }

    private function countParticipants(): array
    {
        $participantsQuery = Participant::query()
            ->selectRaw('count(*) as participations, people.nickname')
            ->join('people', 'people.id', '=', 'participants.person_id')
            ->groupBy('people.id')
            ->orderBy('participations', 'desc')
            ->limit(10)
            ->get();

        $participants = [];

        foreach ($participantsQuery as $participant) {
            $participants[$participant->nickname] = $participant->participations;
        }

        return $participants;
    }

    private function minutesAssis($dates = []): array
    {
        $query = Episode::query()
            ->selectRaw('sum(assis.average_time) as minutes, date')
            ->join('assis', 'assis.id', '=', 'episodes.assis_id')
            ->orderBy('date', 'desc')
            ->groupBy('date')
            ->take(10);

        if (count($dates) > 0) {
            $query->whereBetween('episodes.date', $dates[0], $dates[1]);
        }

        $minutesQuery =  $query->get();

        $minutes = [];

        foreach ($minutesQuery as $minute) {
            $minutes[$minute->date] = $minute->minutes;
        }

        return $minutes;
    }
}
