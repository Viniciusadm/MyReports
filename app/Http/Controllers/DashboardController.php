<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $participants = $this->countParticipants();

        return response()->json([
            'success' => true,
            'data' => [
                'participants' => $participants,
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
}
