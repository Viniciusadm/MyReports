<?php

namespace App\Http\Controllers;

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
                ->select('id', 'question', 'type', 'deactivated_at')
                ->with('answer', function ($query) use ($date) {
                    $query->select('id', 'question_id', 'answer', 'comment', 'created_at')
                        ->where('date', '=', $date);
                })
                ->where('deactivated_at', '>=', $date)
                ->orWhereNull('deactivated_at')
                ->get();

            return response()->json(['success' => true, 'data' => $questions]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $question = Question::query()->create([
                'question' => $request->input('question'),
                'type' => $request->input('type'),
            ]);

            return response()->json(['success' => true, 'data' => $question]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $question = Question::query()->findOrFail($id);
            $question->update(['deactivated_at' => date('Y-m-d')]);
            return response()->json(['success' => true, 'data' => $question]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function changeQuestion(Request $request, int $id): JsonResponse
    {
        try {
            $question = Question::query()->findOrFail($id);
            $question->update([
                'question' => $request->input('question'),
            ]);

            return response()->json(['success' => true, 'data' => $question]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function changeType(Request $request, int $id): JsonResponse
    {
        try {
            $question = Question::query()->findOrFail($id);
            $question->update([
                'type' => $request->input('type'),
            ]);

            return response()->json(['success' => true, 'data' => $question]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
