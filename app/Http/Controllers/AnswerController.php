<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            $question = Answer::query()->create([
                'question_id' => $request->input('question_id'),
                'answer' => $request->input('answer'),
                'comment' => $request->input('comment'),
            ]);

            return response()->json(['success' => true, 'data' => $question]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $question = Answer::query()->findOrFail($id);
            $question->update([
                'question_id' => $request->input('question_id'),
                'answer' => $request->input('answer'),
                'comment' => $request->input('comment'),
            ]);

            return response()->json(['success' => true, 'message' => 'Resposta atualizada com sucesso!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
