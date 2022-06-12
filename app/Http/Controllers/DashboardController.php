<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\Answer;
use App\Models\Assis;
use App\Models\Episode;
use App\Models\Participant;
use App\Models\Person;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function warnings(): JsonResponse
    {
        $assisToday = $this->assisToday();
        $birthdays = $this->nextBirthday();

        $response = array_merge($assisToday, $birthdays);

        return response()->json(ResponseResource::make($response));
    }

    public function participants(): JsonResponse
    {
        $participants = Participant::query()
            ->selectRaw('count(*) as participations, people.nickname, people.id as person_id')
            ->join('people', 'people.id', '=', 'participants.person_id')
            ->groupBy('people.id')
            ->orderBy('participations', 'desc')
            ->limit(10)
            ->where('participants.created_at', '>=', now()->subDays(30))
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
            ->where('episodes.date', '>=', now()->subDays(30))
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

    private function assisToday(): array
    {
        $week = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
        ];

        $assis = Assis::query()
            ->select('id', 'collection_id', 'name')
            ->with('collection', function ($query) {
                $query->select('id', 'name');
            })
            ->where('airing', '=', true)
            ->where('weekday', '=', $week[now()->dayOfWeek])
            ->get();


        $warnings = [];

        foreach ($assis as $assi) {
            $episode = Episode::query()
                ->select('id', 'date')
                ->where('assis_id', '=', $assi->id)
                ->where('date', '>=', now()->subDays(1))
                ->first();

            if (!$episode) {
                $warnings[] = [
                    'message' => 'Episódio novo de <strong>' . $assi->full_name . '</strong>',
                    'priority' => 8,
                ];
            }
        }

        return $warnings;
    }

    private function nextBirthday(): array
    {
        $birthdays = Person::query()
            ->selectRaw('id, nickname, DATE_FORMAT(birth_date, "2022-%m-%d") as birth_date')
            ->where('birth_date', '!=', null)
            ->orderBy('birth_date')
            ->get();

        $warnings = [];

        $today = now()->format('Y-m-d');
        $sevenDays = now()->addDays(8)->format('Y-m-d');

        foreach ($birthdays as $birthday) {
            if ($birthday->birth_date >= $today && $birthday->birth_date < $sevenDays) {
                $day = date('d/m', strtotime($birthday->birth_date));
                $today_formatted = now()->format('d/m');
                $final = $day === $today_formatted  ? ' hoje' : ' em ' . $day;

                $warnings[] = [
                    'message' => 'Aniversário do(a) <strong>' . $birthday->nickname . '</strong>' . $final,
                    'priority' => 8 - (now()->diffInDays($birthday->birth_date) + 1),
                ];
            }
        }

        return $warnings;
    }
}
