<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\ResponseResource;
use App\Models\Question;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(): JsonResponse
    {
        $date = request()->input('date') ?? date('Y-m-d');
        try {
            $questions = Question::query()
                ->select('id', 'question', 'yes', 'no', 'deactivated_at')
                ->with('answer', function ($query) use ($date) {
                    $query->select('id', 'question_id', 'answer', 'comment', 'created_at')
                        ->where('date', '=', $date);
                })
                ->where('deactivated_at', '>=', $date)
                ->orWhereNull('deactivated_at')
                ->get();

            return response()->json(ResponseResource::make($questions));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $question = Question::query()->create([
                'question' => $request->input('question'),
                'yes' => $request->input('yes'),
                'no' => $request->input('no'),
            ]);

            return response()->json(ResponseResource::make($question));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $question = Question::query()
                ->findOrFail($id)
                ->update(['deactivated_at' => date('Y-m-d')]);

            return response()->json(ResponseResource::make($question));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function changeQuestion(Request $request, int $id): JsonResponse
    {
        try {
            $question = Question::query()
                ->findOrFail($id)
                ->update([
                    'question' => $request->input('question'),
                ]);

            return response()->json(ResponseResource::make($question));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function changeYes(Request $request, int $id): JsonResponse
    {
        try {
            $question = Question::query()
                ->findOrFail($id)
                ->update([
                    'yes' => $request->input('yes'),
                ]);

            return response()->json(ResponseResource::make($question));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }
}
