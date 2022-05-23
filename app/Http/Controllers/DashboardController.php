<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\Answer;
use App\Models\Episode;
use App\Models\Participant;
use App\Models\Question;
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

    public function points(): JsonResponse
    {
        $answers = Answer::query()
            ->select('question_id', 'answer', 'date')
            ->where('date', '>', now()->subDays(10))
            ->with('question', function ($query) {
                $query->select('id', 'question', 'yes', 'no');
            })
            ->orderBy('question_id')
            ->get();

        $score = [
            'good' => 1,
            'neutral' => 0,
            'bad' => -1,
        ];

        $points = [];

        foreach ($answers as $answer) {
            if (!isset($points[$answer->date])) {
                $points[$answer->date] = 0;
            } else {
                $points[$answer->date] += $score[$answer->question->{$answer->answer}];
            }
        }

        $points = array_reverse($points);

        return response()->json(ResponseResource::make($points));
    }
}
